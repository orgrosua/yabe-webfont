<template>
    <span class="text-2xl">» {{ __('New', 'yabe-webfont') }} </span>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">


            <div id="post-body-content" style="/* position: relative; */">

                <div id="titlediv">
                    <div id="titlewrap">
                        <label class="screen-reader-text" id="title-prompt-text" for="title">Add title</label>
                        <input type="text" name="post_title" size="30" value="Hello world!" id="title" spellcheck="true" autocomplete="off">
                    </div>
                    <div class="inside">
                        <div id="edit-slug-box" class="hide-if-no-js">
                            <strong>Permalink:</strong>
                            <span id="sample-permalink"><a href="http://127.0.0.1:8080/?p=1&amp;preview=true" target="wp-preview-1">http://127.0.0.1:8080/<span id="editable-post-name">hello-world</span>/</a></span>
                            ‎<span id="edit-slug-buttons"><button type="button" class="edit-slug button button-small hide-if-no-js" aria-label="Edit permalink">Edit</button></span>
                            <span id="editable-post-name-full">hello-world</span>
                        </div>
                    </div>
                    <input type="hidden" id="samplepermalinknonce" name="samplepermalinknonce" value="8c957095a7">
                </div><!-- /titlediv -->
                <div id="postdivrich" class="postarea wp-editor-expand">

                    <div id="wp-content-wrap" class="wp-core-ui wp-editor-wrap tmce-active has-dfw" style="padding-top: 55px;">
                        <link rel="stylesheet" id="editor-buttons-css" href="http://127.0.0.1:8080/wp-includes/css/editor.min.css?ver=6.1.1" media="all">
                        <div id="wp-content-editor-tools" class="wp-editor-tools hide-if-no-js" style="position: absolute; top: 0px; width: 856.4px;">
                            <div id="wp-content-media-buttons" class="wp-media-buttons"><button type="button" id="insert-media-button" class="button insert-media add_media" data-editor="content"><span class="wp-media-buttons-icon"></span> Add Media</button></div>
                            <div class="wp-editor-tabs"><button type="button" id="content-tmce" class="wp-switch-editor switch-tmce" data-wp-editor-id="content">Visual</button>
                                <button type="button" id="content-html" class="wp-switch-editor switch-html" data-wp-editor-id="content">Text</button>
                            </div>
                        </div>
                        <div id="wp-content-editor-container" class="wp-editor-container">
                            <div id="ed_toolbar" class="quicktags-toolbar hide-if-no-js" style="position: absolute; top: 0px; width: 816.4px;"><input type="button" id="qt_content_strong" class="ed_button button button-small" aria-label="Bold" value="b"><input type="button" id="qt_content_em" class="ed_button button button-small" aria-label="Italic" value="i"><input type="button" id="qt_content_link" class="ed_button button button-small" aria-label="Insert link" value="link"><input type="button" id="qt_content_block" class="ed_button button button-small" aria-label="Blockquote" value="b-quote"><input type="button" id="qt_content_del" class="ed_button button button-small" aria-label="Deleted text (strikethrough)" value="del"><input type="button" id="qt_content_ins" class="ed_button button button-small" aria-label="Inserted text" value="ins"><input type="button" id="qt_content_img" class="ed_button button button-small" aria-label="Insert image" value="img"><input type="button" id="qt_content_ul" class="ed_button button button-small" aria-label="Bulleted list" value="ul"><input type="button" id="qt_content_ol" class="ed_button button button-small" aria-label="Numbered list" value="ol"><input type="button" id="qt_content_li" class="ed_button button button-small" aria-label="List item" value="li"><input type="button" id="qt_content_code" class="ed_button button button-small" aria-label="Code" value="code"><input type="button" id="qt_content_more" class="ed_button button button-small" aria-label="Insert Read More tag" value="more"><input type="button" id="qt_content_close" class="ed_button button button-small" title="Close all open tags" value="close tags"><button type="button" id="qt_content_dfw" class="ed_button qt-dfw" title="Distraction-free writing mode"></button></div>
                            <div id="mceu_23" class="mce-tinymce mce-container mce-panel" hidefocus="1" tabindex="-1" role="application" style="visibility: hidden; border-width: 1px; width: 100%;"><grammarly-extension data-grammarly-shadow-root="true" style="position: absolute; top: 0px; left: 0px; pointer-events: none;" class="cGcvT"></grammarly-extension><grammarly-extension data-grammarly-shadow-root="true" style="mix-blend-mode: darken; position: absolute; top: 0px; left: 0px; pointer-events: none;" class="cGcvT"></grammarly-extension>
                                <div id="mceu_23-body" class="mce-container-body mce-stack-layout">
                                    <div id="mceu_24" class="mce-top-part mce-container mce-stack-layout-item mce-first">
                                        <div id="mceu_24-body" class="mce-container-body">
                                            <div id="mceu_25" class="mce-toolbar-grp mce-container mce-panel mce-first mce-last" hidefocus="1" tabindex="-1" role="group" style="position: absolute; top: 0px; width: 854.4px;">
                                                <div id="mceu_25-body" class="mce-container-body mce-stack-layout">
                                                    <div id="mceu_26" class="mce-container mce-toolbar mce-stack-layout-item mce-first" role="toolbar">
                                                        <div id="mceu_26-body" class="mce-container-body mce-flow-layout">
                                                            <div id="mceu_27" class="mce-container mce-flow-layout-item mce-first mce-last mce-btn-group" role="group">
                                                                <div id="mceu_27-body">
                                                                    <div id="mceu_0" class="mce-widget mce-btn mce-menubtn mce-fixed-width mce-listbox mce-first mce-btn-has-text" tabindex="-1" aria-labelledby="mceu_0" role="button" aria-haspopup="true"><button id="mceu_0-open" role="presentation" type="button" tabindex="-1"><span class="mce-txt">Paragraph</span> <i class="mce-caret"></i></button></div>
                                                                    <div id="mceu_1" class="mce-widget mce-btn" tabindex="-1" aria-pressed="false" role="button" aria-label="Bold (Ctrl+B)"><button id="mceu_1-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-bold"></i></button></div>
                                                                    <div id="mceu_2" class="mce-widget mce-btn" tabindex="-1" aria-pressed="false" role="button" aria-label="Italic (Ctrl+I)"><button id="mceu_2-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-italic"></i></button></div>
                                                                    <div id="mceu_3" class="mce-widget mce-btn" tabindex="-1" aria-pressed="false" role="button" aria-label="Bulleted list (Shift+Alt+U)"><button id="mceu_3-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-bullist"></i></button></div>
                                                                    <div id="mceu_4" class="mce-widget mce-btn" tabindex="-1" aria-pressed="false" role="button" aria-label="Numbered list (Shift+Alt+O)"><button id="mceu_4-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-numlist"></i></button></div>
                                                                    <div id="mceu_5" class="mce-widget mce-btn" tabindex="-1" aria-pressed="false" role="button" aria-label="Blockquote (Shift+Alt+Q)"><button id="mceu_5-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-blockquote"></i></button></div>
                                                                    <div id="mceu_6" class="mce-widget mce-btn" tabindex="-1" aria-pressed="false" role="button" aria-label="Align left (Shift+Alt+L)"><button id="mceu_6-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-alignleft"></i></button></div>
                                                                    <div id="mceu_7" class="mce-widget mce-btn" tabindex="-1" aria-pressed="false" role="button" aria-label="Align center (Shift+Alt+C)"><button id="mceu_7-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-aligncenter"></i></button></div>
                                                                    <div id="mceu_8" class="mce-widget mce-btn" tabindex="-1" aria-pressed="false" role="button" aria-label="Align right (Shift+Alt+R)"><button id="mceu_8-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-alignright"></i></button></div>
                                                                    <div id="mceu_9" class="mce-widget mce-btn" tabindex="-1" aria-pressed="false" role="button" aria-label="Insert/edit link (Ctrl+K)"><button id="mceu_9-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-link"></i></button></div>
                                                                    <div id="mceu_10" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Insert Read More tag (Shift+Alt+T)"><button id="mceu_10-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-wp_more"></i></button></div>
                                                                    <div id="mceu_11" class="mce-widget mce-btn mce-last" tabindex="-1" role="button" aria-label="Toolbar Toggle (Shift+Alt+Z)"><button id="mceu_11-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-wp_adv"></i></button></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="mceu_28" class="mce-container mce-toolbar mce-stack-layout-item mce-last" role="toolbar" style="display: none;">
                                                        <div id="mceu_28-body" class="mce-container-body mce-flow-layout">
                                                            <div id="mceu_29" class="mce-container mce-flow-layout-item mce-first mce-last mce-btn-group" role="group">
                                                                <div id="mceu_29-body">
                                                                    <div id="mceu_12" class="mce-widget mce-btn mce-first" tabindex="-1" aria-pressed="false" role="button" aria-label="Strikethrough (Shift+Alt+D)"><button id="mceu_12-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-strikethrough"></i></button></div>
                                                                    <div id="mceu_13" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Horizontal line"><button id="mceu_13-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-hr"></i></button></div>
                                                                    <div id="mceu_14" class="mce-widget mce-btn mce-splitbtn mce-colorbutton" role="button" tabindex="-1" aria-haspopup="true" aria-label="Text color"><button role="presentation" hidefocus="1" type="button" tabindex="-1"><i class="mce-ico mce-i-forecolor"></i><span id="mceu_14-preview" class="mce-preview"></span></button><button type="button" class="mce-open" hidefocus="1" tabindex="-1"> <i class="mce-caret"></i></button></div>
                                                                    <div id="mceu_15" class="mce-widget mce-btn" tabindex="-1" aria-pressed="false" role="button" aria-label="Paste as text"><button id="mceu_15-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-pastetext"></i></button></div>
                                                                    <div id="mceu_16" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Clear formatting"><button id="mceu_16-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-removeformat"></i></button></div>
                                                                    <div id="mceu_17" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Special character"><button id="mceu_17-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-charmap"></i></button></div>
                                                                    <div id="mceu_18" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Decrease indent"><button id="mceu_18-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-outdent"></i></button></div>
                                                                    <div id="mceu_19" class="mce-widget mce-btn" tabindex="-1" role="button" aria-label="Increase indent"><button id="mceu_19-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-indent"></i></button></div>
                                                                    <div id="mceu_20" class="mce-widget mce-btn mce-disabled" tabindex="-1" role="button" aria-label="Undo (Ctrl+Z)" aria-disabled="true"><button id="mceu_20-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-undo"></i></button></div>
                                                                    <div id="mceu_21" class="mce-widget mce-btn mce-disabled" tabindex="-1" role="button" aria-label="Redo (Ctrl+Y)" aria-disabled="true"><button id="mceu_21-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-redo"></i></button></div>
                                                                    <div id="mceu_22" class="mce-widget mce-btn mce-last" tabindex="-1" role="button" aria-label="Keyboard Shortcuts (Shift+Alt+H)"><button id="mceu_22-button" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-wp_help"></i></button></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="mceu_30" class="mce-edit-area mce-container mce-panel mce-stack-layout-item" hidefocus="1" tabindex="-1" role="group" style="border-width: 1px 0px 0px; padding-top: 41px;"><iframe id="content_ifr" frameborder="0" allowtransparency="true" title="Rich Text Area. Press Alt-Shift-H for help." style="width: 100%; height: 334px; display: block;"></iframe></div>
                                    <div id="mceu_31" class="mce-statusbar mce-container mce-panel mce-stack-layout-item mce-last" hidefocus="1" tabindex="-1" role="group" style="visibility: hidden;">
                                        <div id="mceu_31-body" class="mce-container-body mce-flow-layout">
                                            <div id="mceu_32" class="mce-path mce-flow-layout-item mce-first mce-last">
                                                <div role="button" class="mce-path-item mce-last" data-index="0" tabindex="-1" id="mceu_32-0" aria-level="1">p</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><textarea class="wp-editor-area" style="height: 300px; display: none; margin-top: 37px;" autocomplete="off" cols="40" name="content" id="content" aria-hidden="true">&lt;!-- wp:paragraph --&gt;
&lt;p&gt;Welcome to WordPress. This is your first post. Edit or delete it, then start writing!&lt;/p&gt;
&lt;!-- /wp:paragraph --&gt;

&lt;!-- wp:paragraph --&gt;
&lt;p&gt;&lt;/p&gt;
&lt;!-- /wp:paragraph --&gt;

&lt;!-- wp:heading --&gt;
&lt;h2&gt;a &lt;/h2&gt;
&lt;!-- /wp:heading --&gt;</textarea>
                        </div>
                        <div class="uploader-editor">
                            <div class="uploader-editor-content">
                                <div class="uploader-editor-title">Drop files to upload</div>
                            </div>
                        </div>
                    </div>

                    <table id="post-status-info" style="">
                        <tbody>
                            <tr>
                                <td id="wp-word-count" class="hide-if-no-js">
                                    Word count: <span class="word-count">16</span> </td>
                                <td class="autosave-info">
                                    <span class="autosave-message">&nbsp;</span>
                                    <span id="last-edit">Last edited on November 11, 2022 at 4:40 pm</span>
                                </td>
                                <td id="content-resize-handle" class="hide-if-no-js"><br></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>


            <!-- /post-body-content -->
            <div id="postbox-container-1" class="postbox-container">
                <div id="side-sortables" class="meta-box-sortables ui-sortable" style="">
                    <div id="submitdiv" class="postbox ">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">Publish</h2>
                            <div class="handle-actions hide-if-no-js"><button type="button" class="handle-order-higher" aria-disabled="true" aria-describedby="submitdiv-handle-order-higher-description"><span class="screen-reader-text">Move up</span><span class="order-higher-indicator" aria-hidden="true"></span></button><span class="hidden" id="submitdiv-handle-order-higher-description">Move Publish box up</span><button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="submitdiv-handle-order-lower-description"><span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span></button><span class="hidden" id="submitdiv-handle-order-lower-description">Move Publish box down</span><button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Publish</span><span class="toggle-indicator" aria-hidden="true"></span></button></div>
                        </div>
                        <div class="inside">
                            <div class="submitbox" id="submitpost">

                                <div id="minor-publishing">

                                    <div style="display:none;">
                                        <p class="submit"><input type="submit" name="save" id="save" class="button" value="Save"></p>
                                    </div>

                                    <div id="minor-publishing-actions">
                                        <div id="save-action">
                                            <input type="submit" name="save" id="save-post" value="Save Draft" class="button">
                                            <span class="spinner"></span>
                                        </div>

                                        <div id="preview-action">
                                            <a class="preview button" href="http://127.0.0.1:8080/?p=1&amp;preview=true" target="wp-preview-1" id="post-preview">Preview<span class="screen-reader-text"> (opens in a new tab)</span></a>
                                            <input type="hidden" name="wp-preview" id="wp-preview" value="">
                                        </div>
                                        <div class="clear"></div>
                                    </div>

                                    <div id="misc-publishing-actions">
                                        <div class="misc-pub-section misc-pub-post-status">
                                            Status: <span id="post-status-display">
                                                Draft </span>

                                            <a href="#post_status" class="edit-post-status hide-if-no-js" role="button"><span aria-hidden="true">Edit</span> <span class="screen-reader-text">Edit status</span></a>

                                            <div id="post-status-select" class="hide-if-js">
                                                <input type="hidden" name="hidden_post_status" id="hidden_post_status" value="draft">
                                                <label for="post_status" class="screen-reader-text">Set status</label>
                                                <select name="post_status" id="post_status">
                                                    <option value="pending">Pending Review</option>
                                                    <option selected="selected" value="draft">Draft</option>
                                                </select>
                                                <a href="#post_status" class="save-post-status hide-if-no-js button">OK</a>
                                                <a href="#post_status" class="cancel-post-status hide-if-no-js button-cancel">Cancel</a>
                                            </div>
                                        </div>

                                        <div class="misc-pub-section misc-pub-visibility" id="visibility">
                                            Visibility: <span id="post-visibility-display">
                                                Public </span>

                                            <a href="#visibility" class="edit-visibility hide-if-no-js" role="button"><span aria-hidden="true">Edit</span> <span class="screen-reader-text">Edit visibility</span></a>

                                            <div id="post-visibility-select" class="hide-if-js">
                                                <input type="hidden" name="hidden_post_password" id="hidden-post-password" value="">
                                                <input type="checkbox" style="display:none" name="hidden_post_sticky" id="hidden-post-sticky" value="sticky">

                                                <input type="hidden" name="hidden_post_visibility" id="hidden-post-visibility" value="public">
                                                <input type="radio" name="visibility" id="visibility-radio-public" value="public" checked="checked"> <label for="visibility-radio-public" class="selectit">Public</label><br>

                                                <span id="sticky-span"><input id="sticky" name="sticky" type="checkbox" value="sticky"> <label for="sticky" class="selectit">Stick this post to the front page</label><br></span>

                                                <input type="radio" name="visibility" id="visibility-radio-password" value="password"> <label for="visibility-radio-password" class="selectit">Password protected</label><br>
                                                <span id="password-span"><label for="post_password">Password:</label> <input type="text" name="post_password" id="post_password" value="" maxlength="255"><br></span>

                                                <input type="radio" name="visibility" id="visibility-radio-private" value="private"> <label for="visibility-radio-private" class="selectit">Private</label><br>

                                                <p>
                                                    <a href="#visibility" class="save-post-visibility hide-if-no-js button">OK</a>
                                                    <a href="#visibility" class="cancel-post-visibility hide-if-no-js button-cancel">Cancel</a>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="misc-pub-section curtime misc-pub-curtime">
                                            <span id="timestamp">
                                                Publish on: <b>Aug 24, 2022 at 03:11</b> </span>
                                            <a href="#edit_timestamp" class="edit-timestamp hide-if-no-js" role="button">
                                                <span aria-hidden="true">Edit</span>
                                                <span class="screen-reader-text">Edit date and time</span>
                                            </a>
                                            <fieldset id="timestampdiv" class="hide-if-js">
                                                <legend class="screen-reader-text">Date and time</legend>
                                                <div class="timestamp-wrap"><label><span class="screen-reader-text">Month</span><select class="form-required" id="mm" name="mm">
                                                            <option value="01" data-text="Jan">01-Jan</option>
                                                            <option value="02" data-text="Feb">02-Feb</option>
                                                            <option value="03" data-text="Mar">03-Mar</option>
                                                            <option value="04" data-text="Apr">04-Apr</option>
                                                            <option value="05" data-text="May">05-May</option>
                                                            <option value="06" data-text="Jun">06-Jun</option>
                                                            <option value="07" data-text="Jul">07-Jul</option>
                                                            <option value="08" data-text="Aug" selected="selected">08-Aug</option>
                                                            <option value="09" data-text="Sep">09-Sep</option>
                                                            <option value="10" data-text="Oct">10-Oct</option>
                                                            <option value="11" data-text="Nov">11-Nov</option>
                                                            <option value="12" data-text="Dec">12-Dec</option>
                                                        </select></label> <label><span class="screen-reader-text">Day</span><input type="text" id="jj" name="jj" value="24" size="2" maxlength="2" autocomplete="off" class="form-required"></label>, <label><span class="screen-reader-text">Year</span><input type="text" id="aa" name="aa" value="2022" size="4" maxlength="4" autocomplete="off" class="form-required"></label> at <label><span class="screen-reader-text">Hour</span><input type="text" id="hh" name="hh" value="03" size="2" maxlength="2" autocomplete="off" class="form-required"></label>:<label><span class="screen-reader-text">Minute</span><input type="text" id="mn" name="mn" value="11" size="2" maxlength="2" autocomplete="off" class="form-required"></label></div><input type="hidden" id="ss" name="ss" value="27">

                                                <input type="hidden" id="hidden_mm" name="hidden_mm" value="08">
                                                <input type="hidden" id="cur_mm" name="cur_mm" value="12">
                                                <input type="hidden" id="hidden_jj" name="hidden_jj" value="24">
                                                <input type="hidden" id="cur_jj" name="cur_jj" value="20">
                                                <input type="hidden" id="hidden_aa" name="hidden_aa" value="2022">
                                                <input type="hidden" id="cur_aa" name="cur_aa" value="2022">
                                                <input type="hidden" id="hidden_hh" name="hidden_hh" value="03">
                                                <input type="hidden" id="cur_hh" name="cur_hh" value="09">
                                                <input type="hidden" id="hidden_mn" name="hidden_mn" value="11">
                                                <input type="hidden" id="cur_mn" name="cur_mn" value="34">

                                                <p>
                                                    <a href="#edit_timestamp" class="save-timestamp hide-if-no-js button">OK</a>
                                                    <a href="#edit_timestamp" class="cancel-timestamp hide-if-no-js button-cancel">Cancel</a>
                                                </p>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>

                                <div id="major-publishing-actions">
                                    <div id="delete-action">
                                        <a class="submitdelete deletion" href="http://127.0.0.1:8080/wp-admin/post.php?post=1&amp;action=trash&amp;_wpnonce=0693899314">Move to Trash</a>
                                    </div>

                                    <div id="publishing-action">
                                        <span class="spinner"></span>
                                        <input name="original_publish" type="hidden" id="original_publish" value="Publish">
                                        <input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Publish">
                                    </div>
                                    <div class="clear"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div id="categorydiv" class="postbox ">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">Categories</h2>
                            <div class="handle-actions hide-if-no-js"><button type="button" class="handle-order-higher" aria-disabled="false" aria-describedby="categorydiv-handle-order-higher-description"><span class="screen-reader-text">Move up</span><span class="order-higher-indicator" aria-hidden="true"></span></button><span class="hidden" id="categorydiv-handle-order-higher-description">Move Categories box up</span><button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="categorydiv-handle-order-lower-description"><span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span></button><span class="hidden" id="categorydiv-handle-order-lower-description">Move Categories box down</span><button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Categories</span><span class="toggle-indicator" aria-hidden="true"></span></button></div>
                        </div>
                        <div class="inside">
                            <div id="taxonomy-category" class="categorydiv">
                                <ul id="category-tabs" class="category-tabs">
                                    <li class="tabs"><a href="#category-all">All Categories</a></li>
                                    <li class="hide-if-no-js"><a href="#category-pop">Most Used</a></li>
                                </ul>

                                <div id="category-pop" class="tabs-panel" style="display: none;">
                                    <ul id="categorychecklist-pop" class="categorychecklist form-no-clear">
                                    </ul>
                                </div>

                                <div id="category-all" class="tabs-panel">
                                    <input type="hidden" name="post_category[]" value="0">
                                    <ul id="categorychecklist" data-wp-lists="list:category" class="categorychecklist form-no-clear">

                                        <li id="category-1"><label class="selectit"><input value="1" type="checkbox" name="post_category[]" id="in-category-1" checked="checked"> Uncategorized</label></li>
                                    </ul>
                                </div>
                                <div id="category-adder" class="wp-hidden-children">
                                    <a id="category-add-toggle" href="#category-add" class="hide-if-no-js taxonomy-add-new">
                                        + Add New Category </a>
                                    <p id="category-add" class="category-add wp-hidden-child">
                                        <label class="screen-reader-text" for="newcategory">Add New Category</label>
                                        <input type="text" name="newcategory" id="newcategory" class="form-required form-input-tip" value="New Category Name" aria-required="true">
                                        <label class="screen-reader-text" for="newcategory_parent">
                                            Parent Category: </label>
                                        <select name="newcategory_parent" id="newcategory_parent" class="postform">
                                            <option value="-1">— Parent Category —</option>
                                            <option class="level-0" value="1">Uncategorized</option>
                                        </select>
                                        <input type="button" id="category-add-submit" data-wp-lists="add:categorychecklist:category-add" class="button category-add-submit" value="Add New Category">
                                        <input type="hidden" id="_ajax_nonce-add-category" name="_ajax_nonce-add-category" value="5bb74ab04c"> <span id="category-ajax-response"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tagsdiv-post_tag" class="postbox ">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">Tags</h2>
                            <div class="handle-actions hide-if-no-js"><button type="button" class="handle-order-higher" aria-disabled="false" aria-describedby="tagsdiv-post_tag-handle-order-higher-description"><span class="screen-reader-text">Move up</span><span class="order-higher-indicator" aria-hidden="true"></span></button><span class="hidden" id="tagsdiv-post_tag-handle-order-higher-description">Move Tags box up</span><button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="tagsdiv-post_tag-handle-order-lower-description"><span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span></button><span class="hidden" id="tagsdiv-post_tag-handle-order-lower-description">Move Tags box down</span><button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Tags</span><span class="toggle-indicator" aria-hidden="true"></span></button></div>
                        </div>
                        <div class="inside">
                            <div class="tagsdiv" id="post_tag">
                                <div class="jaxtag">
                                    <div class="nojs-tags hide-if-js">
                                        <label for="tax-input-post_tag">Add or remove tags</label>
                                        <p><textarea name="tax_input[post_tag]" rows="3" cols="20" class="the-tags" id="tax-input-post_tag" aria-describedby="new-tag-post_tag-desc"></textarea></p>
                                    </div>
                                    <div class="ajaxtag hide-if-no-js">
                                        <label class="screen-reader-text" for="new-tag-post_tag">Add New Tag</label>
                                        <input data-wp-taxonomy="post_tag" type="text" id="new-tag-post_tag" name="newtag[post_tag]" class="newtag form-input-tip ui-autocomplete-input" size="16" autocomplete="off" aria-describedby="new-tag-post_tag-desc" value="" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-owns="ui-id-2">
                                        <input type="button" class="button tagadd" value="Add">
                                    </div>
                                    <p class="howto" id="new-tag-post_tag-desc">Separate tags with commas</p>
                                </div>
                                <ul class="tagchecklist" role="list"></ul>
                            </div>
                            <p class="hide-if-no-js"><button type="button" class="button-link tagcloud-link" id="link-post_tag" aria-expanded="false">Choose from the most used tags</button></p>
                        </div>
                    </div>
                    <div id="postimagediv" class="postbox ">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">Featured image</h2>
                            <div class="handle-actions hide-if-no-js"><button type="button" class="handle-order-higher" aria-disabled="false" aria-describedby="postimagediv-handle-order-higher-description"><span class="screen-reader-text">Move up</span><span class="order-higher-indicator" aria-hidden="true"></span></button><span class="hidden" id="postimagediv-handle-order-higher-description">Move Featured image box up</span><button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="postimagediv-handle-order-lower-description"><span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span></button><span class="hidden" id="postimagediv-handle-order-lower-description">Move Featured image box down</span><button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Featured image</span><span class="toggle-indicator" aria-hidden="true"></span></button></div>
                        </div>
                        <div class="inside">
                            <p class="hide-if-no-js"><a href="http://127.0.0.1:8080/wp-admin/media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=1&amp;width=753&amp;height=437" id="set-post-thumbnail" class="thickbox">Set featured image</a></p><input type="hidden" id="_thumbnail_id" name="_thumbnail_id" value="-1">
                        </div>
                    </div>
                </div>
            </div>
            <div id="postbox-container-2" class="postbox-container">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable empty-container">
                    <div id="postexcerpt" class="postbox  hide-if-js" style="">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">Excerpt</h2>
                            <div class="handle-actions hide-if-no-js"><button type="button" class="handle-order-higher" aria-disabled="false" aria-describedby="postexcerpt-handle-order-higher-description"><span class="screen-reader-text">Move up</span><span class="order-higher-indicator" aria-hidden="true"></span></button><span class="hidden" id="postexcerpt-handle-order-higher-description">Move Excerpt box up</span><button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="postexcerpt-handle-order-lower-description"><span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span></button><span class="hidden" id="postexcerpt-handle-order-lower-description">Move Excerpt box down</span><button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Excerpt</span><span class="toggle-indicator" aria-hidden="true"></span></button></div>
                        </div>
                        <div class="inside">
                            <label class="screen-reader-text" for="excerpt">Excerpt</label><textarea rows="1" cols="40" name="excerpt" id="excerpt"></textarea>
                            <p>
                                Excerpts are optional hand-crafted summaries of your content that can be used in your theme. <a href="https://wordpress.org/support/article/excerpt/">Learn more about manual excerpts</a>.</p>
                        </div>
                    </div>
                    <div id="trackbacksdiv" class="postbox  hide-if-js" style="">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">Send Trackbacks</h2>
                            <div class="handle-actions hide-if-no-js"><button type="button" class="handle-order-higher" aria-disabled="false" aria-describedby="trackbacksdiv-handle-order-higher-description"><span class="screen-reader-text">Move up</span><span class="order-higher-indicator" aria-hidden="true"></span></button><span class="hidden" id="trackbacksdiv-handle-order-higher-description">Move Send Trackbacks box up</span><button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="trackbacksdiv-handle-order-lower-description"><span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span></button><span class="hidden" id="trackbacksdiv-handle-order-lower-description">Move Send Trackbacks box down</span><button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Send Trackbacks</span><span class="toggle-indicator" aria-hidden="true"></span></button></div>
                        </div>
                        <div class="inside">
                            <p>
                                <label for="trackback_url">Send trackbacks to:</label>
                                <input type="text" name="trackback_url" id="trackback_url" class="code" value="" aria-describedby="trackback-url-desc">
                            </p>
                            <p id="trackback-url-desc" class="howto">Separate multiple URLs with spaces</p>
                            <p>
                                Trackbacks are a way to notify legacy blog systems that you’ve linked to them. If you link other WordPress sites, they’ll be notified automatically using <a href="https://wordpress.org/support/article/introduction-to-blogging/#comments">pingbacks</a>, no other action necessary.</p>
                        </div>
                    </div>
                    <div id="postcustom" class="postbox  hide-if-js" style="">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">Custom Fields</h2>
                            <div class="handle-actions hide-if-no-js"><button type="button" class="handle-order-higher" aria-disabled="false" aria-describedby="postcustom-handle-order-higher-description"><span class="screen-reader-text">Move up</span><span class="order-higher-indicator" aria-hidden="true"></span></button><span class="hidden" id="postcustom-handle-order-higher-description">Move Custom Fields box up</span><button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="postcustom-handle-order-lower-description"><span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span></button><span class="hidden" id="postcustom-handle-order-lower-description">Move Custom Fields box down</span><button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Custom Fields</span><span class="toggle-indicator" aria-hidden="true"></span></button></div>
                        </div>
                        <div class="inside">
                            <div id="postcustomstuff">
                                <div id="ajax-response"></div>

                                <table id="list-table" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th class="left">Name</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody id="the-list" data-wp-lists="list:meta">
                                        <tr>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p><strong>Add New Custom Field:</strong></p>
                                <table id="newmeta">
                                    <thead>
                                        <tr>
                                            <th class="left"><label for="metakeyselect">Name</label></th>
                                            <th><label for="metavalue">Value</label></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td id="newmetaleft" class="left">
                                                <select id="metakeyselect" name="metakeyselect">
                                                    <option value="#NONE#">— Select —</option>

                                                    <option value="bricks_font_faces">bricks_font_faces</option>
                                                    <option value="ct_builder_json">ct_builder_json</option>
                                                    <option value="ct_builder_shortcodes">ct_builder_shortcodes</option>
                                                    <option value="ct_builder_shortcodes_revisions">ct_builder_shortcodes_revisions</option>
                                                    <option value="ct_builder_shortcodes_revisions_dates">ct_builder_shortcodes_revisions_dates</option>
                                                    <option value="ct_page_settings">ct_page_settings</option>
                                                </select>
                                                <input class="hide-if-js" type="text" id="metakeyinput" name="metakeyinput" value="">
                                                <a href="#postcustomstuff" class="hide-if-no-js" onclick="jQuery('#metakeyinput, #metakeyselect, #enternew, #cancelnew').toggle();return false;">
                                                    <span id="enternew">Enter new</span>
                                                    <span id="cancelnew" class="hidden">Cancel</span></a>
                                            </td>
                                            <td><textarea id="metavalue" name="metavalue" rows="2" cols="25"></textarea></td>
                                        </tr>

                                        <tr>
                                            <td colspan="2">
                                                <div class="submit">
                                                    <input type="submit" name="addmeta" id="newmeta-submit" class="button" value="Add Custom Field" data-wp-lists="add:the-list:newmeta">
                                                </div>
                                                <input type="hidden" id="_ajax_nonce-add-meta" name="_ajax_nonce-add-meta" value="f44db26394">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <p>
                                Custom fields can be used to add extra metadata to a post that you can <a href="https://wordpress.org/support/article/custom-fields/">use in your theme</a>.</p>
                        </div>
                    </div>
                    <div id="commentstatusdiv" class="postbox  hide-if-js" style="">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">Discussion</h2>
                            <div class="handle-actions hide-if-no-js"><button type="button" class="handle-order-higher" aria-disabled="false" aria-describedby="commentstatusdiv-handle-order-higher-description"><span class="screen-reader-text">Move up</span><span class="order-higher-indicator" aria-hidden="true"></span></button><span class="hidden" id="commentstatusdiv-handle-order-higher-description">Move Discussion box up</span><button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="commentstatusdiv-handle-order-lower-description"><span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span></button><span class="hidden" id="commentstatusdiv-handle-order-lower-description">Move Discussion box down</span><button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Discussion</span><span class="toggle-indicator" aria-hidden="true"></span></button></div>
                        </div>
                        <div class="inside">
                            <input name="advanced_view" type="hidden" value="1">
                            <p class="meta-options">
                                <label for="comment_status" class="selectit"><input name="comment_status" type="checkbox" id="comment_status" value="open" checked="checked"> Allow comments</label><br>
                                <label for="ping_status" class="selectit"><input name="ping_status" type="checkbox" id="ping_status" value="open" checked="checked">
                                    Allow <a href="https://wordpress.org/support/article/introduction-to-blogging/#managing-comments">trackbacks and pingbacks</a> on this page </label>
                            </p>
                        </div>
                    </div>
                    <div id="slugdiv" class="postbox  hide-if-js" style="">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">Slug</h2>
                            <div class="handle-actions hide-if-no-js"><button type="button" class="handle-order-higher" aria-disabled="false" aria-describedby="slugdiv-handle-order-higher-description"><span class="screen-reader-text">Move up</span><span class="order-higher-indicator" aria-hidden="true"></span></button><span class="hidden" id="slugdiv-handle-order-higher-description">Move Slug box up</span><button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="slugdiv-handle-order-lower-description"><span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span></button><span class="hidden" id="slugdiv-handle-order-lower-description">Move Slug box down</span><button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Slug</span><span class="toggle-indicator" aria-hidden="true"></span></button></div>
                        </div>
                        <div class="inside">
                            <label class="screen-reader-text" for="post_name">Slug</label><input name="post_name" type="text" size="13" id="post_name" value="hello-world">
                        </div>
                    </div>
                    <div id="authordiv" class="postbox  hide-if-js" style="">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">Author</h2>
                            <div class="handle-actions hide-if-no-js"><button type="button" class="handle-order-higher" aria-disabled="false" aria-describedby="authordiv-handle-order-higher-description"><span class="screen-reader-text">Move up</span><span class="order-higher-indicator" aria-hidden="true"></span></button><span class="hidden" id="authordiv-handle-order-higher-description">Move Author box up</span><button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="authordiv-handle-order-lower-description"><span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span></button><span class="hidden" id="authordiv-handle-order-lower-description">Move Author box down</span><button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Author</span><span class="toggle-indicator" aria-hidden="true"></span></button></div>
                        </div>
                        <div class="inside">
                            <label class="screen-reader-text" for="post_author_override">Author</label>
                            <select name="post_author_override" id="post_author_override" class="">
                                <option value="1" selected="selected">admin (admin)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="advanced-sortables" class="meta-box-sortables ui-sortable empty-container"></div>
            </div>
        </div>
    </div>


</template>

<script setup>
</script>