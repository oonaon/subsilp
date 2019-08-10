<?php

use common\models\ItemAlias;
use common\components\CActiveForm;
use yii\widgets\Pjax;
use common\components\HeadNavigator;
use yii\helpers\ArrayHelper;

$this->params['header'] = HeadNavigator::header();
$this->params['breadcrumbs'] = HeadNavigator::breadcrumbs($model->code);

$action_id = Yii::$app->controller->action->id;
if ($action_id == 'view') {
    $button = ['update'];
} else if ($action_id == 'update') {
    $button = ['save', 'cancel'];
    $model_items[] = new common\models\BillItem();
}

$this->params['panel'] = [
    'id' => 'tab',
    'tabs' => $tabs,
    'tabs_disabled' => $action_id == 'update' ? true : false,
    'disabled' => $action_id == 'update' ? false : true,
    'title' => empty($model->id) ? Yii::t('backend/tab', 'add_new') : $model->code,
    'controlbar' => [
        'template_add' => $button,
    ],
];

Pjax::begin();
$form = CActiveForm::begin(['id' => 'control-form', 'enableClientValidation' => false]);
$form->setupScript();
?>
<?= $form->errorSummary($model); ?>
<?= $form->errorSummary($model_items); ?>

<div class="row">
    <?= $form->field($model, 'code', ['options' => ['class' => 'col-xs-6 col-md-3']])->code(['maxlength' => true]) ?>
    <?= $form->field($model, 'date', ['options' => ['class' => 'col-xs-6 col-md-3']])->date(['disabled' => $this->params['panel']['disabled']]) ?>
</div>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="row">
            <?= $form->field($model, 'company_id', ['options' => ['class' => 'col-xs-12 col-md-7']])->selectAjax(['company', 'type' => 'cus'], 3, ['disabled' => $this->params['panel']['disabled']]) ?>
            <?= $form->field($model, 'contact_id', ['options' => ['class' => 'col-xs-12 col-md-5']])->dropDownList(ArrayHelper::map($model->company->contacts, 'id', 'name')) ?>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                    <?php
                    if (!empty($model->company)) {
                        echo $model->company->getFullDetail();
                    }
                    ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="row">
            <?= $form->field($model, 'transport', ['options' => ['class' => 'col-xs-12 col-md-6']])->dropDownList(ItemAlias::getData('transport')) ?>
            <?= $form->field($model, 'vat_type', ['options' => ['class' => 'col-xs-12 col-md-6']])->dropDownList(ItemAlias::getData('vat_type')) ?>
            <?= $form->field($model, 'salesman', ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'reference', ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['maxlength' => true]) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Product</th>
                        <th>Sub</th>
                        <th>Caption</th>
                        <th>Stock</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th>Free</th>
                        <th>Remark</th>
                    </tr>

                </thead>

                <tbody>
                    <?php
                    foreach ($model_items as $key => $item) {
                        $item_id = empty($item['product_id']) ? '' : $item['product_id'];
                        $data_stock = $model->getStockItem($item_id, $item['sub']);
                        $data_sub = $model->getSubItem($item_id);
                        ?>
                        <tr>
                            <td><?= ($key) ?></td>
                            <td>
                                <?= $form->field($item, "[$key]product_id")->selectAjax(['product', 'kind' => '1'], 3, ['disabled' => $this->params['panel']['disabled']])->label(false) ?>
                                <?= $form->field($item, "[$key]old")->hiddenInput()->label(false) ?>
                                <?= $form->field($item, "[$key]id")->hiddenInput()->label(false) ?>
                            </td>
                            <td><?= $form->field($item, "[$key]sub")->dropDownList($data_sub, ['data-pjax-update' => true, 'disabled' => empty($data_sub)])->label(false) ?></td>
                            <td><?= $form->field($item, "[$key]caption")->textInput(['maxlength' => true, 'disabled' => empty($item_id)])->label(false) ?></td>
                            <td><?= $form->field($item, "[$key]stock_id")->dropDownList($data_stock, ['disabled' => empty($data_stock)])->label(false) ?></td>
                            <td><?= $form->field($item, "[$key]quantity")->textInput(['data-pjax-update' => true, 'maxlength' => true, 'disabled' => empty($item_id)])->label(false) ?></td>
                            <td><?= $form->field($item, "[$key]price")->textInput(['placeholder' => $item['price_suggest'], 'data-pjax-update' => true, 'maxlength' => true, 'disabled' => empty($item_id)])->label(false) ?></td>
                            <td><?= $form->field($item, "[$key]discount")->textInput(['data-pjax-update' => true, 'maxlength' => true, 'disabled' => empty($item_id)])->label(false) ?></td>
                            <td><?= $form->field($item, "[$key]total")->textInput(['disabled' => true])->label(false) ?></td>
                            <td><?= $form->field($item, "[$key]free")->textInput(['maxlength' => true, 'disabled' => empty($item_id)])->label(false) ?></td>
                            <td><?= $form->field($item, "[$key]remark")->textInput(['maxlength' => true, 'disabled' => empty($item_id)])->label(false) ?></td>
                        </tr>
                        <?php
                    }
                    ?>

                </tbody>                      

            </table>
        </div>
    </div>
    <!-- /.col -->
</div>

<div class="row">
    <?= $form->field($model, 'remark', ['options' => ['class' => 'col-xs-12 col-md-6']])->textarea(['rows' => 2]) ?>
</div>


<?php
CActiveForm::end();
Pjax::end();
?>
