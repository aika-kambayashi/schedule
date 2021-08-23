<?php $this->setLayoutVar('title', 'スケジュール新規登録画面') ?>

<form action="<?php echo $base_url; ?>/day/new?event_date=<?php echo $event_date; ?>" method="post">
    <?php if(isset($errors) && count($errors) > 0): ?>
        <?php echo $this->render('errors', ['errors' => $errors]); ?>
    <?php endif; ?>
<p>新規予定作成
    <table>
        <tr>
            <th>予定名称</th>
            <td><input type="text" class="form-control" name="event_name" value="<?php echo $this->escape($event_name); ?>" /></td>
        </tr>
        <tr>
            <th>予定日</th>
            <td><input type="date" class="form-control" name="event_date" value="<?php echo $this->escape($event_date); ?>" /></td>
        </tr>
        <tr>
            <th>終日</th>
            <td><input type="checkbox" class="form-control" name="event_allday" value="1" <?php if ($event_allday == '1') echo 'checked="checked"'; ?> /></td>
        </tr>
        <tr>
            <th>開始時刻</th>
            <td><input type="time" class="form-control" name="event_start_time" value="<?php echo 'HH:MM'; ?>" /></td>
        </tr>
        <tr>
            <th>終了時刻</th>
            <td><input type="time" class="form-control" name="event_end_time" value="<?php echo 'HH:MM'; ?>" /></td>
        </tr>
        <tr>
            <th>ユーザ</th>
            <td><select class="form-control" name="user_id">
                <option value="" <?php if($user_id == '') echo 'select="selected"'; ?>>ユーザを選択してください</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['user_id']; ?>" <?php if($user_id == $user['user_id']) echo 'selected="selected"'; ?>><?php echo $user['name']; ?></option>
                <?php endforeach; ?>
            </select></td>
        </tr>
        <tr>
            <th>メモ</th>
            <td>
                <textarea rows="5" cols="70" maxlength="255" name="memo" ></textarea>
            </td>
        </tr>
        <tr>
            <th></th>
            <td><input type="submit" class="btn" value="登録" /></td>
        </tr>
    </table>
</p>
</form>