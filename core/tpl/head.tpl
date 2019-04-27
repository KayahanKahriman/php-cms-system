<!doctype html>
<html lang="{($SYS.HEADER.LANG)?$SYS.HEADER.LANG:'tr'}">
<head>
    <meta name="theme-color" content="{$SYS.HEADER.ThemeColor}">
    <base href="{$SYS.HEADER.BaseUrl}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$SYS.HEADER.Title}</title>

    <meta name="description" content="{$SYS.HEADER.Desc}"/>
    <meta name="keywords" content="{$SYS.HEADER.Keyw}"/>
    <meta name="copyright" content="{$SYS.HEADER.Copy}"/>
    <meta name="author" content="{$SYS.HEADER.Autr}"/>
    <meta name="robots" content="FOLLOW,INDEX"/>

    <link href="{$SYS.HEADER.ShortIcon}" rel="shortcut icon" type="image/x-icon"/>
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{$SYS.HEADER.SiteMap}"/>

    {$SYS.HEADER.GoogleVerification}
    {$SYS.HEADER.GoogleAnalitics}
    {$SYS.HEADER.GoogleBackregister}
    {$SYS.HEADER.Header}

</head>
<body role="main">