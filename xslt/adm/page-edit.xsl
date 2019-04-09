<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" version="1.0">

    <xsl:template match="CMSAdd">
        <h2>
            <a href="/adm/content/pages">
                <xsl:value-of select="$locale/cms/adm/h2"/>
            </a>
            <xsl:text> → </xsl:text>
            <xsl:value-of select="$locale/cms/adm/add-page-title"/>
        </h2>
        <xsl:call-template name="CMSPage"/>
    </xsl:template>

    <xsl:template match="CMSEdit">
        <h2>
            <a href="/adm/content/pages">
                <xsl:value-of select="$locale/cms/adm/h2"/>
            </a>
            <xsl:text> → </xsl:text>
            <xsl:value-of select="$locale/cms/adm/edit-page-title"/>
        </h2>
        <xsl:call-template name="CMSPage"/>
    </xsl:template>

    <xsl:template name="CMSPage">
        <h3>
            <xsl:value-of select="$locale/cms/adm/options"/>
        </h3>
        <form action="/adm/content/pages/save" method="post" class="ajaxer">
            <xsl:if test="@id">
                <input type="hidden" name="id" value="{@id}"/>
            </xsl:if>
            <div class="form-group row">
                <label for="cms-page-title" class="col-sm-2 col-form-label">
                    <xsl:value-of select="$locale/cms/adm/title"/>
                </label>
                <div class="col-sm-10">
                    <input type="text" name="title" value="{@title}" id="cms-page-title" class="form-control"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="cms-page-tag" class="col-sm-2 col-form-label">
                    <xsl:value-of select="$locale/cms/adm/tag"/>
                </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="tag" value="{@uri}" id="cms-page-tag"/>
                </div>
            </div>
            <div class="form-group">
                <label for="cms-page-body">
                    <xsl:value-of select="$locale/cms/adm/body"/>
                </label>
                <textarea name="body" editor="Full" bodyClass="page" cols="" rows="" class="form-control">
                    <xsl:value-of select="@body"/>
                </textarea>
            </div>
            <div class="form-group">
                <input type="submit" value="{$locale/cms/adm/submit}" class="btn btn-primary"/>
            </div>
        </form>
    </xsl:template>
</xsl:stylesheet>
