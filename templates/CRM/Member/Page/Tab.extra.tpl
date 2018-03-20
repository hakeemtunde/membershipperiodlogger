<div id="test">
  <div class="crm-accordion-wrapper open">
    <div class="crm-accordion-header"> Membership Periods</div>
    <div class="crm-accordion-body">
       <div class="crm-block crm-form-block crm-form-title-here-form-block body-test">
          {crmAPI entity="membership" action="get" var="membership" contact_id=$contactId sequential="0"}
          {if isset($membership.values) && count($membership.values) eq 1}
            {capture assign=membershipid}{$membership.values[$membership.id].id}{/capture}
              {crmAPI entity="MembershipPeriod" action="get" var="membershipperiods" membership_id=$membershipid}
              {foreach from=$membershipperiods.values item=memperiods}
                <ul>
                  <li>Term/Period {$memperiods.period}: {$memperiods.start_date|date_format:"%e %b %Y"} - {$memperiods.end_date|date_format:"%e %b %Y"}{if $memperiods.has_payment eq 1}
                      {crmAPI entity="MembershipPeriodPayment" action="get" var="mpps" membership_period_id=$memperiods.id}
                      {capture assign=mcid}{$mpps.values[0].contribution_id}{/capture}
                      {capture assign=mcURL}{crmURL p='civicrm/contact/view/contribution' q="reset=1&id=`$mcid`&cid=`$contactId`&action=view&context=membership&selectedChild=contribute&compId=`$membershipid`&compAction=4"}{/capture}
                      <a href="{$mcURL}" title="{ts}View Payment{/ts}" class="crm-hover-button action-item">{ts}View Payment{/ts}</a>
                    {/if}
                  </li>
                </ul>
              {/foreach}
          {else}
            <div class="messages status no-popup">
             <div class="icon inform-icon"></div>
                 {ts}No Membership record yet.{/ts}
            </div>
          {/if}

       </div>
     </div>
  </div>
</div>
{literal}
<script type="text/javascript">
  cj(function($){
      $('#memberships').append($('#test'));
  });

</script>
{/literal}
