<form method="post" action="/cms_ctrl" id="blog_create_form" enctype="multipart/form-data">
    <div class="myDivHide" id="showblog">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="blog_date">Published Date</label>
                <input type="date" name="blog_date" id="blog_date" class="form-control" required />
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
                <label for="blog_title">Blog title <small>Title for the blog page</small></label>
                <input type="text" name="blog_title" id="blog_title" class="form-control" required />
            </div>
            <div class="col-md-12 form-group">
                <label for="blog_link">Slug
                    <small>Blog path. Use only alphanumerics and "-"" characters</small>
                </label>
                <input type="text" name="blog_link" id="blog_link" class="form-control" required />
            </div>
            <div class="col-md-12 form-group">
                <label for="blog_text">Text/Lead&nbsp;
                    <small>Summarized version. (Max: 300 characters)</small>
                </label>
                <textarea name="blog_text" id="blog_text" class="form-control" required></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="blog_reactText">Content/ Blog</label>
                <div name="blog_richText" id="blog_richText"></div>
            </div>
            <div class="col-md-12">
                <div class="file-area">
                    <label for="blog_image">Image/ Cover image&nbsp;
                        <small>Image should be at least 300x300 pixels. Max file size <?php echo ini_get('upload_max_filesize'); ?>.</small>
                    </label>
                    <input type="file" name="blog_image" id="blog_image" accept="image/x-png,image/jpeg" required />
                    <div class="file-dummy">
                        <div class="success">Image is selected</div>
                        <div class="default">Upload only .png or .jpg file</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="blog_alt">Image ALT</label>
                <input type="text" name="blog_alt" id="blog_alt" class="form-control" required />
            </div>
        </div>
        <!-- <div class="form-group mt-2">
            <input class="button" type="submit" name="blog_create_btn" id="blog_create_btn" value="Create" />
            &nbsp;&nbsp;&nbsp;
            <img src="/assets/lloading.gif" id="loader" style="display: none;" width="25%" />
        </div> -->
        <div class="row mt-2">
            <div class="col-md-12 form-group">
                <input class="button" style="margin-top:30px;" type="submit" name="blog_create_btn" id="blog_create_btn" value="Create Post" />
                <img src="/assets/loading.gif" alt="loading" id="loader" style="display: none;" padding-left:10px; width="25%" />
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        ClassicEditor.create(document.querySelector("#blog_richText"), {
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'link',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'codeBlock',
                    'highlight',
                    'blockQuote',
                ]
            },
        }).then(editor => blog_richText = editor).catch(error => {
            console.error(error);
        });
    });
</script>