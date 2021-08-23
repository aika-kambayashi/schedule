<?php $this->setLayoutVar('title', '月別スケジュール一覧画面') ?>

<form action="<?php echo $base_url; ?>/day/schedule?event_date=<?php echo $ymd; ?>" method="post">
    <?php if(isset($errors) && count($errors) > 0): ?>
        <?php echo $this->render('errors', ['errors' => $errors]); ?>
    <?php endif; ?>

<div>
    <span>
        <?php echo $event_date['year']; ?>年<?php echo $event_date['mon']; ?>月<?php echo $event_date['mday']; ?>日　予定一覧
    </span>
    <span class="link">
        <a href="<?php echo $base_url; ?>/day/new?event_date=<?php echo $ymd; ?>">予定作成</a>
    </span>
</div>

<div>
    <table border="1" width="80%">
        <tr bgcolor="#99d4d8">
            <th>ユーザ名</th>
            <th>終日</th>
            <th>開始時刻</th>
            <th>終了時刻</th>
            <th>予定名称</th>
            <th>編集</th>
            <th>削除</th>
        </tr>
        <?php foreach ($events as $event): ?>
            <tr>
                <td><?php echo $event['name']; ?></td>
                <td><?php echo $event['event_allday']; ?></td>
                <td><?php echo $event['event_start_time'] == '00:00:00' ? '' : $event['event_start_time']; ?></td>
                <td><?php echo $event['event_end_time'] == '00:00:00' ? '' : $event['event_end_time']; ?></td>
                <td><?php echo $event['event_name']; ?></td>
                <?php if ($event['is_this_users_event'] == '1'): ?>
                    <td><a href="<?php echo $base_url; ?>/day/edit?event_id=<?php echo $event['event_id']; ?>">編集</a></td>
                    <td><a href="<?php echo $base_url; ?>/day/delete?event_id=<?php echo $event['event_id']; ?>">削除</a></td>
                <?php else: ?>
                    <td></td>
                    <td></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</div>