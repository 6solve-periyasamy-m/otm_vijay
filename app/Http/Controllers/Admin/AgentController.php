<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AgentRequest;
use App\Models\Customer\Agent;
use App\Models\Customer\Organization;

class AgentController extends Controller
{
    public function index()
    {
        return view('pages.admin.agent.table');
    }

    public function create()
    {
        return view('pages.admin.agent.form');
    }

    public function store(AgentRequest $request)
    {
        $agent = Agent::create($request->getData());
        return redirect()->route('agents.view', ['agent' => $agent,]);
    }

    public function view(Agent $agent)
    {
        $agent->load('organizations');
        return view('pages.admin.agent.view', ['agent' => $agent,]);
    }

    public function edit(Agent $agent)
    {
        return view('pages.admin.agent.form', ['agent' => $agent,]);
    }

    public function update(AgentRequest $request, Agent $agent)
    {
        $agent->update($request->getData());
        $agent->save();
        return redirect()->route('agents.view', ['agent' => $agent,]);
    }

    /**
     * Removes an agent from the organization screen
     * @params Organization $organization
     * @params Agent $agent
     */
    public function deleteAgent(Organization $organization, Agent $agent)
    {
        // sanity check
        if ($organization->id === $agent->organization_id) {
            Agent::find($agent->id)?->delete();
        }
        return redirect()->route('organizations.view' , [$organization->id]);
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();
        return redirect()->route('agents.all');
    }
}
