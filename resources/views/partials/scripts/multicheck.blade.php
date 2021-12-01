<script type="text/javascript">
    function multiChanger(checkbox, selector) {
        $('.' + selector).not(':disabled').prop('checked', $('#' + checkbox).prop('checked'));
    }
</script>
