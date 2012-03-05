<?php $this->pageTitle = Yii::app()->name; ?>

<h1>Single queued files upload example</h1>
<form id="fileupload" action="<?php echo $this->createUrl('upload'); ?>" method="POST" enctype="multipart/form-data">
    <div class="row fileupload-buttonbar">
        <div class="span7">
            <!-- The fileinput-button span is used to style the file input field as button -->
            <span class="btn btn-success fileinput-button">
                <span><i class="icon-plus icon-white"></i> Add files...</span>
                <input type="file" name="files[]" multiple>
            </span>
            <button type="submit" class="btn btn-primary start">
                <i class="icon-upload icon-white"></i> Start upload
            </button>
            <button type="reset" class="btn btn-warning cancel">
                <i class="icon-ban-circle icon-white"></i> Cancel upload
            </button>
            <button type="button" class="btn btn-danger delete">
                <i class="icon-trash icon-white"></i> Delete
            </button>
            <input type="checkbox" class="toggle">
        </div>
        <div class="span5">
            <!-- The global progress bar -->
            <div class="progress progress-success progress-striped active fade">
                <div class="bar" style="width:0%;"></div>
            </div>
        </div>
    </div>
    <!-- The loading indicator is shown during image processing -->
    <div class="fileupload-loading"></div>
    <br>
    <!-- The table listing the files available for upload/download -->
    <table class="table table-striped">
        <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">
            <?php foreach($files as $file): ?>
                <tr class="template-download">
                    <td class="preview">
                        <a href="<?php echo $file->url; ?>" title="<?php echo $file->file; ?>" rel="gallery" download="<?php echo $file->file; ?>"><img src="<?php echo $file->thumbnail_url; ?>"></a>
                    </td>
                    <td class="name">
                        <a href="<?php echo $file->url; ?>" title="<?php echo $file->file; ?>" rel="gallery" download="<?php echo $file->file; ?>"><?php echo $file->file; ?></a>
                    </td>
                    <td class="size"></td>
                    <td colspan="2"></td>
                    <td class="delete">
                        <button class="btn btn-danger" data-type="DELETE" data-url="<?php echo $this->createAbsoluteUrl('delete', array('id' => $file->id)); ?>">
                            <i class="icon-trash icon-white"></i> Удалить
                        </button>
                        <input type="checkbox" name="delete" value="1">
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</form>
<?php
$this->beginWidget('bootstrap.widgets.BootModal', array(
    'id'          => 'modal-gallery',
    'htmlOptions' => array('class' => 'modal modal-gallery hide fade'),
));
?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h3 class="modal-title"></h3>
</div>
<div class="modal-body"><div class="modal-image"></div></div>
<div class="modal-footer">
    <a class="btn btn-primary modal-next">Next <i class="icon-arrow-right icon-white"></i></a>
    <a class="btn btn-info modal-prev"><i class="icon-arrow-left icon-white"></i> Previous</a>
    <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000"><i class="icon-play icon-white"></i> Slideshow</a>
    <a class="btn modal-download" target="_blank"><i class="icon-download"></i> Download</a>
</div>
<?php $this->endWidget(); ?>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name">{%=file.name%}</td>
        <td class="size">{%=o.formatFileSize(file.size)%}</td>
        {% if (file.error) { %}
        <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
        <td>
            <div class="progress progress-success progress-striped active"><div class="bar" style="width:0%;"></div></div>
        </td>
        <td class="start">{% if (!o.options.autoUpload) { %}
            <button class="btn btn-primary">
                <i class="icon-upload icon-white"></i> {%=locale.fileupload.start%}
            </button>
            {% } %}</td>
        {% } else { %}
        <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning">
                <i class="icon-ban-circle icon-white"></i> {%=locale.fileupload.cancel%}
            </button>
            {% } %}</td>
    </tr>
    {% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
        <td></td>
        <td class="name">{%=file.name%}</td>
        <td class="size">{%=o.formatFileSize(file.size)%}</td>
        <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else { %}
        <td class="preview">{% if (file.thumbnail_url) { %}
            <a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
            {% } %}</td>
        <td class="name">
            <a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
        </td>
        <td class="size">{%=o.formatFileSize(file.size)%}</td>
        <td colspan="2"></td>
        {% } %}
        <td class="delete">
            <button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">
                <i class="icon-trash icon-white"></i> {%=locale.fileupload.destroy%}
            </button>
            <input type="checkbox" name="delete" value="1">
        </td>
    </tr>
    {% } %}
</script>

<script src="http://blueimp.github.com/JavaScript-Templates/tmpl.min.js"></script>
<script src="http://blueimp.github.com/JavaScript-Load-Image/load-image.min.js"></script>
<script src="http://blueimp.github.com/JavaScript-Canvas-to-Blob/canvas-to-blob.min.js"></script>
<?php $this->_cs->registerScriptFile('http://blueimp.github.com/Bootstrap-Image-Gallery/js/bootstrap-image-gallery.min.js', CClientScript::POS_END); ?>