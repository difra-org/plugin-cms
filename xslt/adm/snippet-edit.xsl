<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" version="1.0">
    <xsl:template match="snippetAdd">
        <h2>
            <a href="/adm/content/snippets">
                <xsl:value-of select="$locale/cms/adm/snippet/title"/>
            </a>
            <xsl:text> → </xsl:text>
            <xsl:value-of select="$locale/cms/adm/snippet/title-add"/>
        </h2>
        <xsl:call-template name="snippetForm"/>
    </xsl:template>

    <xsl:template match="snippetEdit">
        <h2>
            <a href="/adm/content/snippets">
                <xsl:value-of select="$locale/cms/adm/snippet/title"/>
            </a>
            <xsl:text> → </xsl:text>
            <xsl:value-of select="$locale/cms/adm/snippet/title-edit"/>
        </h2>
        <xsl:call-template name="snippetForm"/>
    </xsl:template>

    <xsl:template name="snippetForm">
        <form action="/adm/content/snippets/save" method="post" class="ajaxer">
            <input type="hidden" name="id" value="{@id}"/>
            <div class="form-group row">
                <label for="cms-snippet-name" class="col-sm-2 col-form-label">
                    <xsl:value-of select="$locale/cms/adm/snippet/name"/>
                </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" value="{@name}" id="cms-snippet-name"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="cms-snippet-desc" class="col-sm-2 col-form-label">
                    <xsl:value-of select="$locale/cms/adm/snippet/description"/>
                </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="description" value="{@description}" id="cms-snippet-desc"/>
                </div>
            </div>
            <div class="form-group">
                <label for="cms-snippet-text">
                    <xsl:value-of select="$locale/cms/adm/snippet/text"/>
                </label>
                <textarea rows="" cols="" name="text" editor="Full" bodyClass="page" id="cms-snippet-text" class="form-control">
                    <xsl:value-of select="."/>
                </textarea>
            </div>
            <div class="form-group">
                <input type="submit" value="{$locale/adm/save}" class="btn btn-primary"/>
            </div>
        </form>
    </xsl:template>
</xsl:stylesheet>