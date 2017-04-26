<div id="subscription" class="subscription buttonAdd"><span></span>Alerts</div>

<div id=subscription-top></div>
<div id="subscription-cover-box">
	<span id="subscription-title">By selecting "Weekly", you are opting into receiving a weekly email alert that shows recent changes to the roadmap. To unsubscribe from these alerts select "None".</span>

	<span id="subscription-message-procesing">{{HTML::image('_img/ajax-loader.gif')}}</span>

	<span id="subscription-message-conflict">Sorry, there was a record conflict. Please try again, or contact us at <a href="mailto:product-operations@mediamath.com">product-operations@mediamath.com</a> if the issue persists.</span>

	<span id="subscription-message-subscribe">You have been subscribed.</span>

	<span id="subscription-message-unsubscribe">You have been unsubscribed.</span>

	<span id="subscription-message-error">Sorry, an error occurred. Please try again, or contact us at <a href="mailto:product-operations@mediamath.com">product-operations@mediamath.com</a> if the issue persists.</span>

	<table id="subscription-cover-message"><tbody>
		<tr>
			<td>
				<label id="subscription-activate" class="name_subscription <?php if ($subscribed == 'weekly') echo 'activate'?>">
					<input type="radio" class="switch_subscription"  name="subscriptionOption" id="subscriptionWeekly" value="weekly" <?php if ($subscribed == 'weekly') echo 'checked'?>> Weekly
				</label>
			</td>
		</tr>
		<tr>
			<td>
				<label id="subscription-none" class="name_subscription <?php if ($subscribed != 'weekly') echo 'activate'?>">
					<input type="radio" class="switch_subscription" name="subscriptionOption" id="subscriptionNone" value="none" <?php if ($subscribed != 'weekly') echo 'checked'?>> None
				</label>
			</td>
		</tr>
	</tbody></table>
	<br>

	<span id="subscription-cover-extra">
		<span id="accept-subscription">Save</span>
	</span>

</div>
