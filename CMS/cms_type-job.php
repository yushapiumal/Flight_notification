<form method="post" action="/cms_ctrl" id="job_create_form" enctype="multipart/form-data">
    <div class="myDivHide" id="showjob">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="job_expDate">Expiry date</label>
                <input type="date" name="job_expDate" id="job_expDate" class="form-control" required />
            </div>
            <div class="col-md-1 form-group">
            </div>
            <div class="col-md-7 form-group">
                <label for="blog_tag">Tags <small>If multiple? use commas to separate</small></label>
                <input type="text" name="blog_tag" id="blog_tag" class="form-control" required />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
            <label for="blog_title">Job title <small>Title for the job page</small></label>
                <input type="text" name="job_title" id="job_title" class="form-control" required />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="job_link">URL <small>URL of original job post</small></label>
                <input type="text" name="job_link" id="job_link" class="form-control" required />
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-md-12 form-group">
                <label for="job_reachText">Rich Text</label>
                <div name="job_reachText" id="job_reachText" class="form-control" required></div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-md-12">
                <div class="file-area">
                    <label for="job_image">Job ad/image <small>Image should be at least 300x300 pixels. Max file size <?php echo ini_get('upload_max_filesize'); ?>.</small></label>
                    <input type="file" name="job_image" id="job_image" accept="image/x-png,image/jpeg" required />
                    <div class="file-dummy">
                        <div class="success">Image is selected</div>
                        <div class="default">Upload only .png or .jpg files.</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="job_alt">Image ALT</label>
                <input type="text" name="job_alt" id="job_alt" class="form-control" required />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <input class="button" style="margin-top:30px;" type="submit" name="job_create_btn" id="job_create_btn" value="Create Post" />
                <img src="/assets/loading.gif" alt="loading" id="loader" style="display: none;" padding-left:10px; width="25%" />
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        // ClassicEditor.create(document.querySelector("#job_reachText"), {
        //     toolbar: {
        //         items: [
        //             'heading',
        //             '|',
        //             'bold',
        //             'italic',
        //             'link',
        //             'bulletedList',
        //             'numberedList',
        //             '|',
        //             'codeBlock',
        //             'highlight',
        //             'blockQuote',
        //         ]
        //     },
        // }).then(editor => job_reachText = editor).catch(error => {
        //     console.error(error);
        // });
    });
</script>