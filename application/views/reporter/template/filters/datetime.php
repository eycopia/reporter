<?php 
 $u = uniqid();
 $idDateField =  $filter['name'] . $u;
?>

<div class="col-sm-2 col-xs-2 col-md-2">
    <label class='form-label'>
        <?php echo $filter['label'];?>:
    </label>
    <input type="text" data-toggle="datetimepicker" data-target="#<?php echo $idDateField; ?>"
           id="<?php echo $idDateField; ?>"
           name="<?php echo $filter['name']; ?>"
           class="form-control datetimepicker-input <?php echo $filter['class']; ?>"
           value="<?php echo $filter['value']; ?>"
           placeholder="<?php echo $filter['label'];?>"
    >
</div>

