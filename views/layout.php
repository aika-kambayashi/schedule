<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php if (isset($title)): echo $this->escape($title) . ' - '; endif; ?>スケジュール管理</title>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $base_url; ?>/css/style.css" /> 
    </head>
    <body>
        <?php if ($session->isAuthenticated()): ?>
            <div id="header">
                <?php if (!($session->get('calendar'))): ?>
                    <span class="top"><a href="<?php echo $base_url; ?>/">TOP</a></span>
                <?php endif; ?>
                    <span class="top">&nbsp;</span>
                    <span class="right"><a href="<?php echo $base_url; ?>/user/logout">ログアウト</a></span>
                    <span class="right">
                        所属部署：<?php $depart_name = $this->defaults['session']->get('department')['depart_name']; ?><?php echo $depart_name; ?>
                    </span>
                    <span class="right">
                        ユーザ名：<?php $name = $this->defaults['session']->get('user')['name']; ?><?php echo $name; ?>さん
                    </span>
            </div>
        <?php endif; ?>
        <div id="main">
            <?php echo $_content; ?>
        </div>
        
    </body>
</html>