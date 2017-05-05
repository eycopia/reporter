<div class="col-sm-2 col-xs-2 col-md-2">
    <label class='form-label'>
        <?php echo $filter['label'];?>:
    </label>
    <input type="text"
           id="<?php echo $filter['name']; ?>"
           name="<?php echo $filter['name']; ?>"
           class="form-control <?php echo $filter['class']; ?>"
           value="<?php echo $filter['value']; ?>"
           placeholder="<?php echo $filter['label'];?>"
    >
</div>

