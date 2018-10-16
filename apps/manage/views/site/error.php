<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">
    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        上面的错误发生在Web服务器处理您的请求时。
    </p>
    <p>
        如果您认为这是服务器错误，请与我们联系。谢谢你！
    </p>

</div>
