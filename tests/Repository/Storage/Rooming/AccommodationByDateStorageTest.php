<?php

namespace Tests\Repository\Storage\Rooming;

use App\Repository\Storage\Rooming\AccommodationByDateStorage;
use Illuminate\Support\Carbon;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsAccommodation;
use Tests\Traits\Model\TestsOrder;
use Tests\Traits\Model\TestsQuote;
use Tests\Traits\Model\TestsTour;

class AccommodationByDateStorageTest extends DatabaseTestCase
{
    use TestsAccommodation, TestsQuote, TestsOrder;

    public function testCreateFromInventory(): void
    {
        $inventory = $this->generateAccommodationInventory();
        $storage = AccommodationByDateStorage::createFromInventory($inventory);
        $this->assertEquals($inventory->room_type_id, $storage->room->id);
        $this->assertEquals($inventory->board_type_id, $storage->board->id);
        $this->assertEquals($inventory->room_category_id, $storage->category?->id);
    }

    public function testGetItineraryLines(): void
    {
        // Setup Initial Data
        $accommodation = $this->generateAccommodation();
        $room = $this->generateRoomType();
        $board = $this->generateBoardType();
        // Test that 3 lines become one with no quantity
        $quote = $this->generateQuote();
        for ($i = 0; $i < 3; $i++) {
            $inventory = $this->generateAccommodationInventory($accommodation, $room, $board, ['check_in' => $this->getNow($i), 'check_out' => $this->getNow($i+1)]);
            $inventory->repository->addToQuote($quote, 'Included');
        }
        $quote->refresh();
        $items = $quote->repository->getAccommodationForItinerary();
        $this->assertCount(1, $items);
        $this->assertEquals($this->getNow()->format('d M Y'), $items[0]->details['Check In']);
        $this->assertEquals($this->getNow(3)->format('d M Y'), $items[0]->details['Check Out']);

        // Test that 3 lines become one with equal quantity
        $quote = $this->generateQuote();
        for ($i = 0; $i < 3; $i++) {
            $inventory = $this->generateAccommodationInventory($accommodation, $room, $board, ['check_in' => $this->getNow($i), 'check_out' => $this->getNow($i+1)]);
            $component = $inventory->repository->addToQuote($quote, 'Included');
            $component->update(['quantity' => 3]);
        }
        $quote->refresh();
        $items = $quote->repository->getAccommodationForItinerary();
        $this->assertCount(1, $items);
        $this->assertEquals($this->getNow()->format('d M Y'), $items[0]->details['Check In']);
        $this->assertEquals($this->getNow(3)->format('d M Y'), $items[0]->details['Check Out']);

        // Test that 3 lines become two where first room has more quantity than rest
        $quote = $this->generateQuote();
        for ($i = 0; $i < 3; $i++) {
            $inventory = $this->generateAccommodationInventory($accommodation, $room, $board, ['check_in' => $this->getNow($i), 'check_out' => $this->getNow($i+1)]);
            $component = $inventory->repository->addToQuote($quote, 'Included');
            $component->update(['quantity' => ($i === 0 ? 3 : 2)]);
        }
        $quote->refresh();
        $items = $quote->repository->getAccommodationForItinerary();
        $this->assertCount(2, $items);
        // One quantity, since it has one more quantity than the rest, covering the same duration
        $this->assertEquals(1, $items[0]->details['Quantity']);
        $this->assertEquals($this->getNow()->format('d M Y'), $items[0]->details['Check In']);
        $this->assertEquals($this->getNow(1)->format('d M Y'), $items[0]->details['Check Out']);
        // Second one has two quantity, covering the same duration
        $this->assertEquals(2, $items[1]->details['Quantity']);
        $this->assertEquals($this->getNow()->format('d M Y'), $items[1]->details['Check In']);
        $this->assertEquals($this->getNow(3)->format('d M Y'), $items[1]->details['Check Out']);

        // Test that 3 lines become two where first room has less quantity than rest
        $quote = $this->generateQuote();
        for ($i = 0; $i < 3; $i++) {
            $inventory = $this->generateAccommodationInventory($accommodation, $room, $board, ['check_in' => $this->getNow($i), 'check_out' => $this->getNow($i+1)]);
            $component = $inventory->repository->addToQuote($quote, 'Included');
            $component->update(['quantity' => ($i === 0 ? 2 : 3)]);
        }
        $quote->refresh();
        $items = $quote->repository->getAccommodationForItinerary();
        $this->assertCount(2, $items);
        // First row only covers 2 quantity, since it has a lower quantity
        $this->assertEquals(2, $items[0]->details['Quantity']);
        $this->assertEquals($this->getNow()->format('d M Y'), $items[0]->details['Check In']);
        $this->assertEquals($this->getNow(3)->format('d M Y'), $items[0]->details['Check Out']);
        $this->assertEquals(1, $items[1]->details['Quantity']);
        $this->assertEquals($this->getNow(1)->format('d M Y'), $items[1]->details['Check In']);
        $this->assertEquals($this->getNow(3)->format('d M Y'), $items[1]->details['Check Out']);

        // Test that 3 lines become 2 where first room and last room has less quantity than rest
        $quote = $this->generateQuote();
        for ($i = 0; $i < 3; $i++) {
            $inventory = $this->generateAccommodationInventory($accommodation, $room, $board, ['check_in' => $this->getNow($i), 'check_out' => $this->getNow($i+1)]);
            $component = $inventory->repository->addToQuote($quote, 'Included');
            $component->update(['quantity' => ($i === 1 ? 2 : 1)]); // 0 -> 1, 1 -> 2, 2 -> 1
        }
        $quote->refresh();
        $items = $quote->repository->getAccommodationForItinerary();
        $this->assertCount(2, $items);
        // First row only covers 2 quantity, since it has a lower quantity
        $this->assertEquals(1, $items[0]->details['Quantity']);
        $this->assertEquals($this->getNow()->format('d M Y'), $items[0]->details['Check In']);
        $this->assertEquals($this->getNow(3)->format('d M Y'), $items[0]->details['Check Out']);
        $this->assertEquals(1, $items[1]->details['Quantity']);
        $this->assertEquals($this->getNow(1)->format('d M Y'), $items[1]->details['Check In']);
        $this->assertEquals($this->getNow(2)->format('d M Y'), $items[1]->details['Check Out']);


        // Test that 3 lines become 3 where first room and last room has more quantity than rest
        $quote = $this->generateQuote();
        for ($i = 0; $i < 3; $i++) {
            $inventory = $this->generateAccommodationInventory($accommodation, $room, $board, ['check_in' => $this->getNow($i), 'check_out' => $this->getNow($i+1)]);
            $component = $inventory->repository->addToQuote($quote, 'Included');
            $component->update(['quantity' => ($i === 1 ? 1 : 2)]); // 0 -> 2, 1 -> 1, 2 -> 2
        }
        $quote->refresh();
        $items = $quote->repository->getAccommodationForItinerary();
        $this->assertCount(3, $items);
        // First row only covers 2 quantity, since it has a lower quantity
        $this->assertEquals(1, $items[0]->details['Quantity']);
        $this->assertEquals($this->getNow()->format('d M Y'), $items[0]->details['Check In']);
        $this->assertEquals($this->getNow(1)->format('d M Y'), $items[0]->details['Check Out']);
        $this->assertEquals(1, $items[1]->details['Quantity']);
        $this->assertEquals($this->getNow()->format('d M Y'), $items[1]->details['Check In']);
        $this->assertEquals($this->getNow(3)->format('d M Y'), $items[1]->details['Check Out']);
        $this->assertEquals(1, $items[2]->details['Quantity']);
        $this->assertEquals($this->getNow(2)->format('d M Y'), $items[2]->details['Check In']);
        $this->assertEquals($this->getNow(3)->format('d M Y'), $items[2]->details['Check Out']);

    }

    public function testGetItineraryLinesWithOrder(): void
    {
        // Setup Initial Data
        $accommodation = $this->generateAccommodation();
        $room = $this->generateRoomType();
        $board = $this->generateBoardType();
        // Test that 3 lines become one with no quantity
        $
        $order = $this->generateOrder();
        for ($i = 0; $i < 3; $i++) {
            $inventory = $this->generateAccommodationInventory($accommodation, $room, $board, ['check_in' => $this->getNow($i), 'check_out' => $this->getNow($i+1)]);
            $inventory->repository->addToTour($order, 'Included');
        }
        $order->refresh();
        $items = $order->repository->getAccommodationForItinerary();
        $this->assertCount(1, $items);
        $this->assertEquals($this->getNow()->format('d M Y'), $items[0]->details['Check In']);
        $this->assertEquals($this->getNow(3)->format('d M Y'), $items[0]->details['Check Out']);

        // Test that 3 lines become one with equal quantity
        $order = $this->generateOrder();
        for ($i = 0; $i < 3; $i++) {
            $inventory = $this->generateAccommodationInventory($accommodation, $room, $board, ['check_in' => $this->getNow($i), 'check_out' => $this->getNow($i+1)]);
            $component = $inventory->repository->addToOrder($order, 'Included');
            $component->update(['quantity' => 3]);
        }
        $order->refresh();
        $items = $order->repository->getAccommodationForItinerary();
        $this->assertCount(1, $items);
        $this->assertEquals($this->getNow()->format('d M Y'), $items[0]->details['Check In']);
        $this->assertEquals($this->getNow(3)->format('d M Y'), $items[0]->details['Check Out']);

        // Test that 3 lines become two where first room has more quantity than rest
        $order = $this->generateOrder();
        for ($i = 0; $i < 3; $i++) {
            $inventory = $this->generateAccommodationInventory($accommodation, $room, $board, ['check_in' => $this->getNow($i), 'check_out' => $this->getNow($i+1)]);
            $component = $inventory->repository->addToOrder($order, 'Included');
            $component->update(['quantity' => ($i === 0 ? 3 : 2)]);
        }
        $order->refresh();
        $items = $order->repository->getAccommodationForItinerary();
        $this->assertCount(2, $items);
        // One quantity, since it has one more quantity than the rest, covering the same duration
        $this->assertEquals(1, $items[0]->details['Quantity']);
        $this->assertEquals($this->getNow()->format('d M Y'), $items[0]->details['Check In']);
        $this->assertEquals($this->getNow(1)->format('d M Y'), $items[0]->details['Check Out']);
        // Second one has two quantity, covering the same duration
        $this->assertEquals(2, $items[1]->details['Quantity']);
        $this->assertEquals($this->getNow()->format('d M Y'), $items[1]->details['Check In']);
        $this->assertEquals($this->getNow(3)->format('d M Y'), $items[1]->details['Check Out']);

        // Test that 3 lines become two where first room has less quantity than rest
        $order = $this->generateOrder();
        for ($i = 0; $i < 3; $i++) {
            $inventory = $this->generateAccommodationInventory($accommodation, $room, $board, ['check_in' => $this->getNow($i), 'check_out' => $this->getNow($i+1)]);
            $component = $inventory->repository->addToOrder($order, 'Included');
            $component->update(['quantity' => ($i === 0 ? 2 : 3)]);
        }
        $order->refresh();
        $items = $order->repository->getAccommodationForItinerary();
        $this->assertCount(2, $items);
        // First row only covers 2 quantity, since it has a lower quantity
        $this->assertEquals(2, $items[0]->details['Quantity']);
        $this->assertEquals($this->getNow()->format('d M Y'), $items[0]->details['Check In']);
        $this->assertEquals($this->getNow(3)->format('d M Y'), $items[0]->details['Check Out']);
        $this->assertEquals(1, $items[1]->details['Quantity']);
        $this->assertEquals($this->getNow(1)->format('d M Y'), $items[1]->details['Check In']);
        $this->assertEquals($this->getNow(3)->format('d M Y'), $items[1]->details['Check Out']);

        // Test that 3 lines become 2 where first room and last room has less quantity than rest
        $order = $this->generateOrder();
        for ($i = 0; $i < 3; $i++) {
            $inventory = $this->generateAccommodationInventory($accommodation, $room, $board, ['check_in' => $this->getNow($i), 'check_out' => $this->getNow($i+1)]);
            $component = $inventory->repository->addToOrder($order, 'Included');
            $component->update(['quantity' => ($i === 1 ? 2 : 1)]); // 0 -> 1, 1 -> 2, 2 -> 1
        }
        $order->refresh();
        $items = $order->repository->getAccommodationForItinerary();
        $this->assertCount(2, $items);
        // First row only covers 2 quantity, since it has a lower quantity
        $this->assertEquals(1, $items[0]->details['Quantity']);
        $this->assertEquals($this->getNow()->format('d M Y'), $items[0]->details['Check In']);
        $this->assertEquals($this->getNow(3)->format('d M Y'), $items[0]->details['Check Out']);
        $this->assertEquals(1, $items[1]->details['Quantity']);
        $this->assertEquals($this->getNow(1)->format('d M Y'), $items[1]->details['Check In']);
        $this->assertEquals($this->getNow(2)->format('d M Y'), $items[1]->details['Check Out']);


        // Test that 3 lines become 3 where first room and last room has more quantity than rest
        $order = $this->generateOrder();
        for ($i = 0; $i < 3; $i++) {
            $inventory = $this->generateAccommodationInventory($accommodation, $room, $board, ['check_in' => $this->getNow($i), 'check_out' => $this->getNow($i+1)]);
            $component = $inventory->repository->addToOrder($order, 'Included');
            $component->update(['quantity' => ($i === 1 ? 1 : 2)]); // 0 -> 2, 1 -> 1, 2 -> 2
        }
        $order->refresh();
        $items = $order->repository->getAccommodationForItinerary();
        $this->assertCount(3, $items);
        //dd($items);
        // First row only covers 2 quantity, since it has a lower quantity
        $this->assertEquals(1, $items[0]->details['Quantity']);
        $this->assertEquals($this->getNow()->format('d M Y'), $items[0]->details['Check In']);
        $this->assertEquals($this->getNow(1)->format('d M Y'), $items[0]->details['Check Out']);
        $this->assertEquals(1, $items[1]->details['Quantity']);
        $this->assertEquals($this->getNow()->format('d M Y'), $items[1]->details['Check In']);
        $this->assertEquals($this->getNow(3)->format('d M Y'), $items[1]->details['Check Out']);
        $this->assertEquals(1, $items[2]->details['Quantity']);
        $this->assertEquals($this->getNow(2)->format('d M Y'), $items[2]->details['Check In']);
        $this->assertEquals($this->getNow(3)->format('d M Y'), $items[2]->details['Check Out']);

    }

    private function getNow(int $days = 0): Carbon
    {
        return now()->setTime(0,0,0)->addDays($days);
    }
}
