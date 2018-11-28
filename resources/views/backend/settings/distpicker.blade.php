@if (isset($_GET['_pjax']))
<script>window.location.reload();</script>
@endif
<div class="input-group">
    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
    <div id="distPicker">
        <div class="form-group col-md-4" style="margin-bottom: 0;">
            <label class="sr-only" for="province">Province</label>
            <select class="form-control" id="province" name="province"></select>
        </div>
        <div class="form-group col-md-4"  style="margin-bottom: 0;">
            <label class="sr-only" for="city">City</label>
            <select class="form-control" id="city" name="city"></select>
        </div>
        <div class="form-group col-md-4"  style="margin-bottom: 0;">
            <label class="sr-only" for="district">District</label>
            <select class="form-control" id="district" name="district"></select>
        </div>
    </div>
</div>

<script>
    $("#province").on('change', function () {
        $("#province_id").val($("#province option:selected").attr('data-code'));
    });
    $("#city").on('change', function () {
        $("#city_id").val($("#city option:selected").attr('data-code'));
    });
    $("#district").on('change', function () {
        $("#district_id").val($("#district option:selected").attr('data-code'));
    });
</script>
<input type="text" id="province_id" name="province_id" hidden>
<input type="text" id="city_id" name="city_id" hidden>
<input type="text" id="district_id" name="district_id" hidden>
<script type="text/javascript" src="{{ URL('/common/plugins/distpicker/js/distpicker.js') }}"></script>
<script type="text/javascript" src="{{ URL('/common/plugins/distpicker/js/main.js') }}"></script>
<script type="text/javascript">
    $(function () {
        $('#distPicker').distpicker({
            province: '{{ $client->province }}',
            city: '{{ $client->city }}',
            district: '{{ $client->district }}',
            autoSelect: false,
        });
    });
</script>