<?php
/**
 * New eNevada Organization Administration Screen
 */

// no direct access
defined('ABSPATH') or die('No direct access');

// variables
$resource = new en_Resource();

// get the id
if(filter_has_var(INPUT_POST, 'id')){
	$resource->id = filter_has_var(INPUT_POST, 'id') ? filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT) : 0;
}else{
	$resource->id = filter_has_var(INPUT_GET, 'id') ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) : 0;
}

// save the data
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	// retrieve the input
	$resource->categories = filter_has_var(INPUT_POST, 'categories') ? $_POST['categories'] : array();
	$resource->description = filter_has_var(INPUT_POST, 'description') ? $_POST['description'] : '';
	$resource->name = filter_has_var(INPUT_POST, 'name') ? $_POST['name'] : '';
	$resource->org = filter_has_var(INPUT_POST, 'org') ? $_POST['org'] : 0;
    $resource->slug = filter_has_var(INPUT_POST, 'slug') ? $_POST['slug'] : '';
	$resource->status = filter_has_var(INPUT_POST, 'status') ? $_POST['status'] : '';
	$resource->telephone = filter_has_var(INPUT_POST, 'telephone') ? $_POST['telephone'] : '';
	$resource->website = filter_has_var(INPUT_POST, 'website') ? $_POST['website'] : '';
	
	// validate
	if($resource->save()){
		if(filter_has_var(INPUT_POST, 'saveAndClose')){
			echo '<script type="text/javascript">window.location.href = "/wp-admin/admin.php?page=enrm";</script>';
		}
		$message = 'Resource saved!';
	}else{
		$notice = $resource->getLastError();
	}
}else{
	// load the resource information from the database
	$resource->load();
}
?>
<script type="text/javascript">
	jQuery(function($){
		// format the telephone field as the user types
   	$('#telephone').mask('(999) 999-9999');
	});

	/*
    jQuery Masked Input Plugin
    Copyright (c) 2007 - 2015 Josh Bush (digitalbush.com)
    Licensed under the MIT license (http://digitalbush.com/projects/masked-input-plugin/#license)
    Version: 1.4.1
	*/
	!function(factory) {
    "function" == typeof define && define.amd ? define([ "jquery" ], factory) : factory("object" == typeof exports ? require("jquery") : jQuery);
	}(function($) {
    var caretTimeoutId, ua = navigator.userAgent, iPhone = /iphone/i.test(ua), chrome = /chrome/i.test(ua), android = /android/i.test(ua);
    $.mask = {
      definitions: {
        "9": "[0-9]",
        a: "[A-Za-z]",
        "*": "[A-Za-z0-9]"
      },
      autoclear: !0,
      dataName: "rawMaskFn",
      placeholder: "_"
    }, $.fn.extend({
        caret: function(begin, end) {
            var range;
            if (0 !== this.length && !this.is(":hidden")) return "number" == typeof begin ? (end = "number" == typeof end ? end : begin, 
            this.each(function() {
                this.setSelectionRange ? this.setSelectionRange(begin, end) : this.createTextRange && (range = this.createTextRange(), 
                range.collapse(!0), range.moveEnd("character", end), range.moveStart("character", begin), 
                range.select());
            })) : (this[0].setSelectionRange ? (begin = this[0].selectionStart, end = this[0].selectionEnd) : document.selection && document.selection.createRange && (range = document.selection.createRange(), 
            begin = 0 - range.duplicate().moveStart("character", -1e5), end = begin + range.text.length), 
            {
                begin: begin,
                end: end
            });
        },
        unmask: function() {
            return this.trigger("unmask");
        },
        mask: function(mask, settings) {
            var input, defs, tests, partialPosition, firstNonMaskPos, lastRequiredNonMaskPos, len, oldVal;
            if (!mask && this.length > 0) {
                input = $(this[0]);
                var fn = input.data($.mask.dataName);
                return fn ? fn() : void 0;
            }
            return settings = $.extend({
                autoclear: $.mask.autoclear,
                placeholder: $.mask.placeholder,
                completed: null
            }, settings), defs = $.mask.definitions, tests = [], partialPosition = len = mask.length, 
            firstNonMaskPos = null, $.each(mask.split(""), function(i, c) {
                "?" == c ? (len--, partialPosition = i) : defs[c] ? (tests.push(new RegExp(defs[c])), 
                null === firstNonMaskPos && (firstNonMaskPos = tests.length - 1), partialPosition > i && (lastRequiredNonMaskPos = tests.length - 1)) : tests.push(null);
            }), this.trigger("unmask").each(function() {
                function tryFireCompleted() {
                    if (settings.completed) {
                        for (var i = firstNonMaskPos; lastRequiredNonMaskPos >= i; i++) if (tests[i] && buffer[i] === getPlaceholder(i)) return;
                        settings.completed.call(input);
                    }
                }
                function getPlaceholder(i) {
                    return settings.placeholder.charAt(i < settings.placeholder.length ? i : 0);
                }
                function seekNext(pos) {
                    for (;++pos < len && !tests[pos]; ) ;
                    return pos;
                }
                function seekPrev(pos) {
                    for (;--pos >= 0 && !tests[pos]; ) ;
                    return pos;
                }
                function shiftL(begin, end) {
                    var i, j;
                    if (!(0 > begin)) {
                        for (i = begin, j = seekNext(end); len > i; i++) if (tests[i]) {
                            if (!(len > j && tests[i].test(buffer[j]))) break;
                            buffer[i] = buffer[j], buffer[j] = getPlaceholder(j), j = seekNext(j);
                        }
                        writeBuffer(), input.caret(Math.max(firstNonMaskPos, begin));
                    }
                }
                function shiftR(pos) {
                    var i, c, j, t;
                    for (i = pos, c = getPlaceholder(pos); len > i; i++) if (tests[i]) {
                        if (j = seekNext(i), t = buffer[i], buffer[i] = c, !(len > j && tests[j].test(t))) break;
                        c = t;
                    }
                }
                function androidInputEvent() {
                    var curVal = input.val(), pos = input.caret();
                    if (oldVal && oldVal.length && oldVal.length > curVal.length) {
                        for (checkVal(!0); pos.begin > 0 && !tests[pos.begin - 1]; ) pos.begin--;
                        if (0 === pos.begin) for (;pos.begin < firstNonMaskPos && !tests[pos.begin]; ) pos.begin++;
                        input.caret(pos.begin, pos.begin);
                    } else {
                        for (checkVal(!0); pos.begin < len && !tests[pos.begin]; ) pos.begin++;
                        input.caret(pos.begin, pos.begin);
                    }
                    tryFireCompleted();
                }
                function blurEvent() {
                    checkVal(), input.val() != focusText && input.change();
                }
                function keydownEvent(e) {
                    if (!input.prop("readonly")) {
                        var pos, begin, end, k = e.which || e.keyCode;
                        oldVal = input.val(), 8 === k || 46 === k || iPhone && 127 === k ? (pos = input.caret(), 
                        begin = pos.begin, end = pos.end, end - begin === 0 && (begin = 46 !== k ? seekPrev(begin) : end = seekNext(begin - 1), 
                        end = 46 === k ? seekNext(end) : end), clearBuffer(begin, end), shiftL(begin, end - 1), 
                        e.preventDefault()) : 13 === k ? blurEvent.call(this, e) : 27 === k && (input.val(focusText), 
                        input.caret(0, checkVal()), e.preventDefault());
                    }
                }
                function keypressEvent(e) {
                    if (!input.prop("readonly")) {
                        var p, c, next, k = e.which || e.keyCode, pos = input.caret();
                        if (!(e.ctrlKey || e.altKey || e.metaKey || 32 > k) && k && 13 !== k) {
                            if (pos.end - pos.begin !== 0 && (clearBuffer(pos.begin, pos.end), shiftL(pos.begin, pos.end - 1)), 
                            p = seekNext(pos.begin - 1), len > p && (c = String.fromCharCode(k), tests[p].test(c))) {
                                if (shiftR(p), buffer[p] = c, writeBuffer(), next = seekNext(p), android) {
                                    var proxy = function() {
                                        $.proxy($.fn.caret, input, next)();
                                    };
                                    setTimeout(proxy, 0);
                                } else input.caret(next);
                                pos.begin <= lastRequiredNonMaskPos && tryFireCompleted();
                            }
                            e.preventDefault();
                        }
                    }
                }
                function clearBuffer(start, end) {
                    var i;
                    for (i = start; end > i && len > i; i++) tests[i] && (buffer[i] = getPlaceholder(i));
                }
                function writeBuffer() {
                    input.val(buffer.join(""));
                }
                function checkVal(allow) {
                    var i, c, pos, test = input.val(), lastMatch = -1;
                    for (i = 0, pos = 0; len > i; i++) if (tests[i]) {
                        for (buffer[i] = getPlaceholder(i); pos++ < test.length; ) if (c = test.charAt(pos - 1), 
                        tests[i].test(c)) {
                            buffer[i] = c, lastMatch = i;
                            break;
                        }
                        if (pos > test.length) {
                            clearBuffer(i + 1, len);
                            break;
                        }
                    } else buffer[i] === test.charAt(pos) && pos++, partialPosition > i && (lastMatch = i);
                    return allow ? writeBuffer() : partialPosition > lastMatch + 1 ? settings.autoclear || buffer.join("") === defaultBuffer ? (input.val() && input.val(""), 
                    clearBuffer(0, len)) : writeBuffer() : (writeBuffer(), input.val(input.val().substring(0, lastMatch + 1))), 
                    partialPosition ? i : firstNonMaskPos;
                }
                var input = $(this), buffer = $.map(mask.split(""), function(c, i) {
                    return "?" != c ? defs[c] ? getPlaceholder(i) : c : void 0;
                }), defaultBuffer = buffer.join(""), focusText = input.val();
                input.data($.mask.dataName, function() {
                    return $.map(buffer, function(c, i) {
                        return tests[i] && c != getPlaceholder(i) ? c : null;
                    }).join("");
                }), input.one("unmask", function() {
                    input.off(".mask").removeData($.mask.dataName);
                }).on("focus.mask", function() {
                    if (!input.prop("readonly")) {
                        clearTimeout(caretTimeoutId);
                        var pos;
                        focusText = input.val(), pos = checkVal(), caretTimeoutId = setTimeout(function() {
                            input.get(0) === document.activeElement && (writeBuffer(), pos == mask.replace("?", "").length ? input.caret(0, pos) : input.caret(pos));
                        }, 10);
                    }
                }).on("blur.mask", blurEvent).on("keydown.mask", keydownEvent).on("keypress.mask", keypressEvent).on("input.mask paste.mask", function() {
                    input.prop("readonly") || setTimeout(function() {
                        var pos = checkVal(!0);
                        input.caret(pos), tryFireCompleted();
                    }, 0);
                }), chrome && android && input.off("input.mask").on("input.mask", androidInputEvent), 
                checkVal();
            });
        }
    });
	});
</script>
<style type="text/css">
	.required:after{
		color: red;
		content: '*';
	}
</style>
<?php if($notice): ?>
<div id="notice" class="notice notice-warning"><p id="has-newer-autosave"><?php echo $notice ?></p></div>
<?php endif; ?>
<?php if($message): ?>
<div id="message" class="updated"><p><?php echo $message; ?></p></div>
<?php endif; ?>
<div id="lost-connection-notice" class="error hidden">
	<p><span class="spinner"></span> <?php _e( '<strong>Connection lost.</strong> Saving has been disabled until you&#8217;re reconnected.' ); ?>
	<span class="hide-if-no-sessionstorage"><?php _e( 'We&#8217;re backing up this post in your browser, just in case.' ); ?></span>
	</p>
</div>
<form name="post" action="" method="post" id="post">
	<input type="hidden" name="id" value="<?php echo $resource->id; ?>">
    <input type="hidden" name="slug" value="<?php echo $resource->slug; ?>">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label for="status" class="required">Status</label></th>
				<td>
					<select name="status" id="status">
						<option value="publish"<?php echo $resource->status == 'publish' ? ' selected="selected"' : ''; ?>>Published</option>
						<option value="draft"<?php echo $resource->status == 'draft' ? ' selected="selected"' : ''; ?>>Draft</option>
						<option value="trash"<?php echo $resource->status == 'trash' ? ' selected="selected"' : ''; ?>>Trash</option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="name" class="required">Resource Name</label></th>
				<td><input name="name" id="name" value="<?php echo $resource->name; ?>" class="regular-text" type="text" maxlength="250"></td>
			</tr>
			<tr>
				<th scope="row"><label for="org" class="required">Organization</label></th>
				<td>
					<select name="org" id="org">
						<option value="0">-- Select An Organization --</option>
						<?php 
						$organizations = en_get_organizations($resource->org);
						foreach($organizations as $org): 
						?>
							<option value="<?php echo $org->id; ?>"<?php echo ($resource->org == $org->id ? ' selected' : ''); ?>><?php echo $org->name; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="description" class="required">Description</label></th>
				<td><textarea name="description" id="description" style="height:100px;width:350px;"><?php echo $resource->description; ?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><label for="telephone">Telephone Number</label></th>
				<td><input name="telephone" id="telephone" value="<?php echo $resource->telephone; ?>" class="regular-text" type="text" maxlength="14"></td>
			</tr>
			<tr>
				<th scope="row"><label for="website">Website Address</label></th>
				<td><input name="website" id="website" value="<?php echo $resource->website; ?>" class="regular-text" type="text" maxlength="100"></td>
			</tr>
			<tr>
				<th scope="row"><label class="required">Categories</label></th>
				<td>
					<?php 
					$categories = en_get_categories();
					foreach($categories as $cat):
					?>
						<label for="cat<?php echo $cat->id; ?>"><input type="checkbox" name="categories[]"  id="cat<?php echo $cat->id; ?>" value="<?php echo $cat->id; ?>"<?php echo (in_array($cat->id, $resource->categories) ? ' checked' : ''); ?>> <?php echo $cat->name; ?></label><br>
					<?php endforeach; ?>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input name="save" id="save" class="button button-primary" value="Save" type="submit">
		<input name="saveAndClose" id="saveAndClose" class="button button-primary" value="Save &amp; Close" type="submit">
	</p>