<?php

class CalendarController extends Controller {

    protected $auth_actions = ['calendar', 'new'];

    public function calendarAction() {
        $this->session->set('calendar', '1');

        $ym = (!$this->request->getGet('ym')) ? date('Y-m') : $this->request->getGet('ym');
        
        $today = getdate();
        
        $tm = strtotime($ym);
        $dt = getdate($tm);		//現在時刻
        $fm = mktime(0,0,0,$dt["mon"],1,$dt["year"]);	//月初
        $fw = date("w", $fm);	//月初曜日
        $ld = date("t", $tm);	//月終日
        $le = mktime(0,0,0,$dt["mon"],$ld,$dt["year"]);	//月最終日
        $lw = date("w", $le);	//月最終曜日
        $ym = date("Y-m", $tm);
        $m = date("m", $tm);
        
        //前月
        $lastym = date("Y-m",strtotime("-1 month", $tm));
        //翌月
        $nextym = date("Y-m",strtotime("+1 month", $tm));
        
        $wd = ["月","火","水","木","金"];

        $plans = $this->db_manager->get('Calendar')->countEvent($ym);
        $plans = array_column($plans, 'count', 'event_date');

        return $this->render([
            'today' => $today,
            'dt'    => $dt,
            'm'     => $m,
            'fw'    => $fw,
            'ld'    => $ld,
            'lw'    => $lw,
            'ym'    => $ym,
            'lastym' => $lastym,
            'nextym' => $nextym,
            'wd'    => $wd,
            'plans' => $plans,
        ]);

    }

    public function newAction() {
        $this->session->remove('calendar');
        $users = $this->db_manager->get('Day')->getUsers();
        $user = $this->session->get('user');
        $event_date = '';
        $event_allday = '';
        $event_start_time = '';
        $event_end_time = '';
        $event_name = '';
        $user_id = '';
        $errors = [];
        
        if ($this->request->isPost()) {
            $user_id = $this->request->getPost('user_id');
            $event_date = $this->request->getPost('event_date');
            $event_allday = $this->request->getPost('event_allday');
            $event_start_time = $this->request->getPost('event_start_time');
            $event_end_time = $this->request->getPost('event_end_time');
            $event_name = $this->request->getPost('event_name');
            $memo = $this->request->getPost('memo');
            $registered_user_id = $user['user_id'];

            if (!strlen($event_name)) 
                $errors[] = '予定名称を入力してください';

            if (empty($event_date) || !strtotime($event_date))
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

            if ($event_start_time == '00:00' || $event_end_time == '00:00')
                $errors[] = '時刻を入力してください';

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


}