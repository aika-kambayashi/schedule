<?php

class ScheduleApplication extends Application {
    //認証されていない場合に遷移するコントローラ名とアクションの配列指定
    
    protected $login_action = ['user', 'login'];

    public function getRootDir() {
        return dirname(__FILE__);
    }

    protected function registerRoutes() {
        return [
            '/'
                => ['controller' => 'calendar', 'action' => 'calendar'],
            '/user/:action'
                => ['controller' => 'user'],
            '/day/:action'
                => ['controller' => 'day'],  
            '/calendar'
                => ['controller' => 'calendar', 'action' => 'calendar'],          
            '/calendar/new'
                => ['controller' => 'calendar', 'action' => 'new']
        ];
    }

    protected function configure() {
        $this->db_manager->connect('master', [
            'dsn'      => 'mysql:dbname=schedule;host=localhost;charset=utf8',
            'user'     => 'root',
            'password' => '',
        ]);
    }
}
