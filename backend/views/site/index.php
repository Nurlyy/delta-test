<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">

        <h1>Панель администрирования</h1>

    </div>
    <div class="body-content" style="margin-top:60px;">
        <div class="col-12 row">
            <div class="col-6">
                <div class="card">
                    <div class="panel">
                        <div class="panel-header text-center">
                            <br>
                            <a href="/backend/languages" style="text-decoration:none; color:black !important;">
                                <h3>Языки программирования для тестов</h3>
                            </a>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div style="display:flex; justify-content:center;padding:15px;">
                                    <img src="../images/categories.jpg" height="200" style="border-radius:15px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="panel">
                        <div class="panel-header text-center">
                            <br>
                            <a href="/backend/user" style="text-decoration:none; color:black !important;">
                                <h3>Пользователи</h3>
                            </a>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div style="display:flex; justify-content:center;padding:15px;">
                                    <img src="../images/user.png" height="200" style="border-radius:15px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>