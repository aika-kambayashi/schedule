<?php

class DayController extends Controller {

    protected $auth_actions = ['delete', 'edit', 'new', 'schedule'];

    public function deleteAction() {
        $user = $this->session->get('user');
        $event_id = $this->request->getGet('event_id');
        $is_user = implode($this->db_manager->get('Day')->isUser($user['user_id'],$event_id));
        $event_date = implode($this->db_manager->get('Day')->getEventDate($event_id));

        if ($is_user == '1') {
            $this->db_manager->get('Day')->delete($event_id);
            return $this->redirect('/');
        }
        return $this->redirect('/day/schedule?event_date=' . $event_date);
    }

    public function editAction() {
        $this->session->remove('calendar');

        $user = $this->session->get('user');
        $users = $this->db_manager->get('Day')->getUsers();
        $event_id = $this->request->getGet('event_id');
        $is_user = implode($this->db_manager->get('Day')->isUser($user['user_id'],$event_id));
        $event_date = implode($this->db_manager->get('Day')->getEventDate($event_id));
        $event_allday = '';
        $errors = [];

        if ($is_user == '1') {
            if ($this->request->isPost()) {
                $user_id = $this->request->getPost('user_id');
                $event_date = $this->request->getPost('event_date');
                $event_date = $this->request->getPost('event_date');
                $event_allday = $this->request->getPost('event_allday');
                $event_start_time = $this->request->getPost('event_start_time');
                $event_end_time = $this->request->getPost('event_end_time');
                $event_name = $this->request->getPost('event_name');
                $memo = $this->request->getPost('memo');

                if (!strlen($event_name)) 
                    $errors[] = '予定名称を入力してください';

                if (empty($event_date))
                    $errors[] = '予定日を入力してください';

                if (empty($event_allday)) {
                    if (empty($event_start_time) || empty($event_end_time)) {
                        $errors[] = '終日か時刻をどちらかを設定してください';
                    } else if ($event_start_time == '00:00' || $event_end_time == '00:00') {
                        $errors[] = '時刻を入力してください';
                    }
                }

                if (!empty($event_allday)) {
                    if ($event_start_time == '00:00' || $event_end_time == '00:00') {
                        $errors[] = '終日か時刻をどちらかを選択してください';
                    } else if (!empty($event_start_time) || !empty($event_end_time)) {
                            $errors[] = '終日か時刻をどちらかを選択してください';
                    }
                }
                
                if (empty($user_id))
                    $errors[] = 'ユーザを選択してください';

                if (count($errors) === 0) {
                    $this->db_manager->get('Day')->update($event_id,$user_id,$event_date,$event_allday,$event_start_time,$event_end_time,$event_name,$memo);
                    return $this->redirect('/');
                }

                return $this->render([
                    'event_id'  => $event_id,
                    'event_date' => $event_date,
                    'event_allday' => $event_allday,
                    'event_start_time' => $event_start_time,
                    'event_end_time' => $event_end_time,
                    'event_name' => $event_name,
                    'user_id'   => $user_id,
                    'users' => $users,
                    'memo' => $memo,
                    'errors' => $errors
                ]);
            }
            
            $user_id = implode($this->db_manager->get('Day')->getUserId($event_id));
            $event_allday = implode($this->db_manager->get('Day')->getEventAllDay($event_id));
            $event_start_time = implode($this->db_manager->get('Day')->getEventStart($event_id));
            $event_end_time = implode($this->db_manager->get('Day')->getEventEnd($event_id));
            $event_name = implode($this->db_manager->get('Day')->getEventName($event_id));
            $memo = implode($this->db_manager->get('Day')->getMemo($event_id));

            return $this->render([
                'event_id'  => $event_id,
                'event_date' => $event_date,
                'event_allday' => $event_allday,
                'event_start_time' => $event_start_time,
                'event_end_time' => $event_end_time,
                'event_name' => $event_name,
                'user_id'   => $user_id,
                'users' => $users,
                'memo' => $memo
            ]);
        }

        return $this->redirect('/');
    }

    public function newAction () {
        $this->session->remove('calendar');
        $users = $this->db_manager->get('Day')->getUsers();
        $user = $this->session->get('user');
        $event_allday = '';
        $event_start_time = '';
        $event_end_time = '';
        $event_name = '';
        $user_id = '';
        $errors = [];
        
        if ($this->request->isPost()) {
            $user_id = $this->request->getPost('user_id');
            $event_date = $this->request->getPost('event_date');
            $event_date = $this->request->getPost('event_date');
            $event_allday = $this->request->getPost('event_allday');
            $event_start_time = $this->request->getPost('event_start_time');
            $event_end_time = $this->request->getPost('event_end_time');
            $event_name = $this->request->getPost('event_name');
            $memo = $this->request->getPost('memo');
            $registered_user_id = $user['user_id'];

            if (!strlen($event_name)) 
                $errors[] = '予定名称を入力してください';

            if (empty($event_date))
                $errors[] = '予定日を入力してください';

            if (empty($event_allday)) {
                if (empty($event_start_time) || empty($event_end_time)) {
                    $errors[] = '終日か時刻をどちらかを設定してください';
                } else if ($event_start_time == '00:00' || $event_end_time == '00:00') {
                    $errors[] = '時刻を入力してください';
                }
            }

            if (!empty($event_allday)) {
                if ($event_start_time == '00:00' || $event_end_time == '00:00') {
                    $errors[] = '終日か時刻をどちらかを選択してください';
                } else if (!empty($event_start_time) || !empty($event_end_time)) {
                        $errors[] = '終日か時刻をどちらかを選択してください';
                }
            }

            if (empty($user_id))
                $errors[] = 'ユーザを選択してください';

            if (count($errors) === 0) {
                $this->db_manager->get('Day')->insert($user_id,$event_date,$event_allday,$event_start_time,$event_end_time,$event_name,$memo,$registered_user_id);
                return $this->redirect('/');
            }

            return $this->render([
                'event_date' => $event_date,
                'event_allday' => $event_allday,
                'event_start_time' => $event_start_time,
                'event_end_time' => $event_end_time,
                'event_name' => $event_name,
                'user_id'   => $user_id,
                'users' => $users,
                'errors' => $errors
            ]);

        } else {
            $event_date = $this->request->getGet('event_date');

            return $this->render([
                'event_date' => $event_date,
                'event_allday' => $event_allday,
                'event_start_time' => $event_start_time,
                'event_end_time' => $event_end_time,
                'event_name' => $event_name,
                'user_id'   => $user_id,
                'users' => $users
            ]);
        }

    }

    public function scheduleAction (){
        $this->session->remove('calendar');

        $user = $this->session->get('user');
        $ymd = $this->request->getGet('event_date');
        $tm = strtotime($ymd);
        $event_date = getdate($tm);
        $ymd = date("Y-m-d", $tm);

        $events = $this->db_manager->get('Day')->getEventsByEventDate($ymd,$user['user_id']);

        return $this->render([
            'events'     => $events,
            'event_date' => $event_date,
            'ymd'        => $ymd
        ]);

    }

}