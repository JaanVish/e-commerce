
<div class="main-title">
    <h3 class="mb-20">
        {{__('frontendCms.add_repair')}} </h3>
</div>
@include('frontendcms::repair.components.form',['form_id' => 'item_create_form', 'button_level_name' => __('common.save') ])