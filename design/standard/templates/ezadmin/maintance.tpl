<p>The Website "{ezini("SiteSettings","SiteName")}" is down for maintance.</p>

{section show=$time|gt(0)}
    <p>Please try again later. The Service will be avialable again at {$time|datetime('ISO8601')}.</p>
{section-else}
    <p>Please try again later. The Service will be avialable as soon as possible.</p>
{/section}
<p>If you have futher questions, mail to {ezini("MailSettings","AdminEmail")|wash(email)}.</p>