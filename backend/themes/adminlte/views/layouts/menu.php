<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= strtoupper(Yii::$app->session['organize']) ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <!-- /.search form -->

        <?=
        dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                    'items' => [
                        //['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                        // ****** MAIN MENU ******
                        ['label' => Yii::t('backend/menu', 'main'), 'options' => ['class' => 'header']],
                        // BUY MENU
                        [
                            'label' => Yii::t('backend/menu', 'buy'),
                            'icon' => 'cart-arrow-down',
                            'url' => '#',
                            'items' => [
                                ['label' => Yii::t('backend/menu', 'supplier'), 'icon' => 'file-code-o', 'url' => ['supplier/index'],'active' =>(Yii::$app->controller->id=='supplier'?true:false)],
                                ['label' => 'buy 2', 'icon' => 'dashboard', 'url' => ['/debug'],],
                                [
                                    'label' => 'buy 3',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        ['label' => 'sub buy 1', 'icon' => 'circle-o', 'url' => '#',],
                                        ['label' => 'sub buy 2', 'icon' => 'circle-o', 'url' => '#',],
                                    ],
                                ],
                            ],
                        ],
                        // SELL MENU
                        [
                            'label' => Yii::t('backend/menu', 'sell'),
                            'icon' => 'dollar',
                            'url' => '#',
                            'items' => [
                                ['label' => Yii::t('backend/menu', 'customer'), 'icon' => 'file-code-o', 'url' => ['customer/index'],'active' =>(Yii::$app->controller->id=='customer'?true:false)],
                                ['label' => 'sell 2', 'icon' => 'dashboard', 'url' => ['/debug'],],
                                [
                                    'label' => 'sell 3',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        ['label' => 'sub sell 1', 'icon' => 'circle-o', 'url' => '#',],
                                        ['label' => 'sub sell 2', 'icon' => 'circle-o', 'url' => '#',],
                                    ],
                                ],
                            ],
                        ],
                        // MANUFACTURER MENU
                        [
                            'label' => Yii::t('backend/menu', 'manufacture'),
                            'icon' => 'industry',
                            'url' => '#',
                            'items' => [
                                ['label' => Yii::t('backend/menu', 'injector'), 'icon' => 'file-code-o', 'url' => ['manufacturer/index'],'active' =>(Yii::$app->controller->id=='manufacturer'?true:false)],
                                ['label' => 'manu 2', 'icon' => 'dashboard', 'url' => ['/debug'],],
                                [
                                    'label' => 'manu 3',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        ['label' => 'sub sell 1', 'icon' => 'circle-o', 'url' => '#',],
                                        ['label' => 'sub sell 2', 'icon' => 'circle-o', 'url' => '#',],
                                    ],
                                ],
                            ],
                        ],
                        // PRODUCT MENU
                        [
                            'label' => Yii::t('backend/menu', 'product'),
                            'icon' => 'cube',
                            'url' => '#',
                            'items' => [
                                ['label' => 'product 1', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                                ['label' => 'product 2', 'icon' => 'dashboard', 'url' => ['/debug'],],
                                [
                                    'label' => 'product 3',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        ['label' => 'sub product 1', 'icon' => 'circle-o', 'url' => '#',],
                                        ['label' => 'sub product 2', 'icon' => 'circle-o', 'url' => '#',],
                                    ],
                                ],
                            ],
                        ],
                        // ****** SETTING ******
                        ['label' => Yii::t('backend/menu', 'setting'), 'options' => ['class' => 'header']],
                        // BUY MENU
                        [
                            'label' => Yii::t('backend/menu', 'web'),
                            'icon' => 'desktop',
                            'url' => '#',
                            'items' => [
                                ['label' => Yii::t('backend/menu', 'language'), 'icon' => 'language', 'url' => ['language/index'],],
                                ['label' => Yii::t('backend/menu', 'gii'), 'icon' => 'file-code-o', 'url' => ['/gii'],],
                                ['label' => Yii::t('backend/menu', 'debug'), 'icon' => 'bug', 'url' => ['/debug'],],
                            ],
                        ],
                        // ****** OTHER ******
                        ['label' => Yii::t('backend/menu', 'other'), 'options' => ['class' => 'header']],
                        // BUY MENU
                        [
                            'label' => Yii::t('backend/menu', 'link'),
                            'icon' => 'desktop',
                            'url' => '#',
                            'items' => [
                                ['label' => 'AdminLTE', 'icon' => 'language', 'url' => 'https://adminlte.io/themes/AdminLTE/pages/UI/icons.html',],
                            ],
                        ],
                    ],
                ]
        )
        ?>

    </section>

</aside>
