<?php

class CalendarRepository extends DbRepository {

    public function countEvent($event_date) {
        $sql = 'select event_date,count(*) count from event where event_date like :event_date and is_delete = "0" group by event_date order by event_date';
        return $this->fetchAll($sql, [':event_date' => $event_date . '%']);
    }



}