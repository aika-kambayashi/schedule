<?php

class DayRepository extends DbRepository {

    public function isUser($user_id,$event_id) {
        $sql = 'select count(*)
            from event e
            left join user u
            on u.user_id = e.user_id
            where e.user_id = :user_id and event_id = :event_id';
        return $this->fetch($sql, [
            ':user_id' => $user_id,
            ':event_id' => $event_id
        ]);
    }

    public function delete($event_id) {
        $sql = 'update event set is_delete = "1" where event_id = :event_id ';
        return $this->execute($sql, [':event_id' => $event_id]);
    }

    public function insert($user_id,$event_date,$event_allday,$event_start_time,$event_end_time,$event_name,$memo,$registered_user_id) {
        $now = new DateTime();
        $sql = '
            insert into event
                (user_id,event_date,event_allday,event_start_time,event_end_time,event_name,memo,registered_user_id,modified_at,created_at) 
            values
                (:user_id,:event_date,:event_allday,:event_start_time,:event_end_time,:event_name,:memo,:registered_user_id,:modified_at,:created_at)
        ';
        $stmt = $this->execute($sql, [
            'user_id'       => $user_id,
            'event_date'    => $event_date,
            'event_allday'  => $event_allday,
            'event_start_time' => $event_start_time,
            'event_end_time' => $event_end_time,
            'event_name'    => $event_name,
            'memo'          => $memo,
            'registered_user_id' => $registered_user_id,
            ':modified_at'  => $now->format('Y-m-d H:i:s'),
            ':created_at'   => $now->format('Y-m-d H:i:s')
        ]);
    }

    public function update($event_id,$user_id,$event_date,$event_allday,$event_start_time,$event_end_time,$event_name,$memo) {
        $now = new DateTime();
        $sql = '
            update
                event
            set
                user_id=:user_id,
                event_date=:event_date,
                event_allday=:event_allday,
                event_start_time=:event_start_time,
                event_end_time=:event_end_time,
                event_name=:event_name,
                memo=:memo,
                modified_at=:modified_at
            where
                event_id=:event_id

        ';
        $stmt = $this->execute($sql, [
            'event_id'      => $event_id,
            'user_id'       => $user_id,
            'event_date'    => $event_date,
            'event_allday'  => $event_allday,
            'event_start_time' => $event_start_time,
            'event_end_time' => $event_end_time,
            'event_name'    => $event_name,
            'memo'          => $memo,
            ':modified_at'  => $now->format('Y-m-d H:i:s')
        ]);
    }

    public function getUsers() {
        $sql = 'select user_id,name from user';
        return $this->fetchAll($sql);
    }

    public function getEventsByEventDate($event_date,$user_id) {
        $sql = 'select 
                name,
                event_id,
                case when event_allday is null then null else "終日" end event_allday,
                event_start_time,
                event_end_time,
                event_name,
                case when e.user_id = :user_id then "1" else null end is_this_users_event
            from event as e
            left join user u
            on e.user_id = u.user_id
            where event_date = :event_date and is_delete = "0"
            order by e.user_id, event_start_time asc';
        return $this->fetchAll($sql, [
            ':event_date'   => $event_date,
            ':user_id'      => $user_id,
        ]);
    }

    public function getUserId($event_id) {
        $sql = 'select user_id from event where event_id = :event_id';
        return $this->fetch($sql, [':event_id' => $event_id]);
    }

    public function getEventDate($event_id) {
        $sql = 'select event_date from event where event_id = :event_id';
        return $this->fetch($sql, [':event_id' => $event_id]);
    }

    public function getEventAllDay($event_id) {
        $sql = 'select event_allday from event where event_id = :event_id';
        return $this->fetch($sql, [':event_id' => $event_id]);
    }

    public function getEventStart($event_id) {
        $sql = 'select event_start_time from event where event_id = :event_id';
        return $this->fetch($sql, [':event_id' => $event_id]);
    }

    public function getEventEnd($event_id) {
        $sql = 'select event_end_time from event where event_id = :event_id';
        return $this->fetch($sql, [':event_id' => $event_id]);
    }

    public function getEventName($event_id) {
        $sql = 'select event_name from event where event_id = :event_id';
        return $this->fetch($sql, [':event_id' => $event_id]);
    }

    public function getMemo($event_id) {
        $sql = 'select memo from event where event_id = :event_id';
        return $this->fetch($sql, [':event_id' => $event_id]);
    }

}