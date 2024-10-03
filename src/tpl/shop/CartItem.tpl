{* Smarty *}
{$price}
{if $expires === 0 }
    (always available)
{else}
    (available at {$expires|format_date:"c"})
{/if}
