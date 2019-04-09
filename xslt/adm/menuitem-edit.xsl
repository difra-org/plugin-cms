<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" version="1.0">
    <xsl:template match="CMSMenuItemAdd">
        <h2>
            <a href="/adm/content/menu">
                <xsl:value-of select="$locale/cms/adm/menu/h2"/>
            </a>
            <xsl:text> → </xsl:text>
            <a href="/adm/content/menu/view/{@id}">
                <xsl:value-of select="$locale/cms/adm/items/h2"/>
            </a>
            <xsl:text> → </xsl:text>
            <xsl:value-of select="$locale/cms/adm/menuitem/add-item-title"/>
        </h2>
        <h3>
            <xsl:value-of select="$locale/cms/adm/menuitem/options"/>
        </h3>
        <xsl:call-template name="CMSMenuItem"/>
    </xsl:template>

    <xsl:template match="CMSMenuItemEdit">
        <h2>
            <a href="/adm/content/menu">
                <xsl:value-of select="$locale/cms/adm/menu/h2"/>
            </a>
            <xsl:text> → </xsl:text>
            <a href="/adm/content/menu/view/{@id}">
                <xsl:value-of select="$locale/cms/adm/items/h2"/>
            </a>
            <xsl:text> → </xsl:text>
            <xsl:value-of select="$locale/cms/adm/menuitem/edit-item-title"/>
        </h2>
        <xsl:call-template name="CMSMenuItem"/>
    </xsl:template>

    <xsl:template name="CMSMenuItem">
        <div class="form-group row">
            <label for="cms-menuitem-type" class="col-sm-2 col-form-label">
                <xsl:value-of select="$locale/cms/adm/menuitem/type"/>
            </label>
            <div class="col-sm-10">
                <select name="type" onchange="cms.switchItemForm(this.value)" id="cms-menuitem-type" class="form-control">
                    <option value="page">
                        <xsl:value-of select="$locale/cms/adm/menuitem/type-page"/>
                    </option>
                    <option value="link">
                        <xsl:if test="name()='CMSMenuItemEdit' and not(@page) and (@link)">
                            <xsl:attribute name="selected">selected</xsl:attribute>
                        </xsl:if>
                        <xsl:value-of select="$locale/cms/adm/menuitem/type-link"/>
                    </option>
                    <option value="empty">
                        <xsl:if test="name()='CMSMenuItemEdit' and not(@page) and not (@link)">
                            <xsl:attribute name="selected">selected</xsl:attribute>
                        </xsl:if>
                        <xsl:value-of select="$locale/cms/adm/menuitem/type-empty"/>
                    </option>
                </select>
            </div>
        </div>
        <xsl:call-template name="CMSMenuItemParentSelect"/>
        <!-- Add/edit page -->
        <div id="pageForm" class="menuItemForm">
            <xsl:choose>
                <xsl:when test="page">
                    <form action="/adm/content/menu/savepage" method="post" class="ajaxer">
                        <input type="hidden" name="menu" value="{@menu}"/>
                        <input type="hidden" name="id" value="{@id}"/>
                        <input type="hidden" name="parent" value="{@parent}"/>
                        <div class="form-group row">
                            <label for="cms-menuitem-page" class="col-sm-2 col-form-label">
                                <xsl:value-of select="$locale/cms/adm/menuitem/type-page"/>
                            </label>
                            <div class="col-sm-10">
                                <select name="page" class="form-control">
                                    <xsl:for-each select="page">
                                        <option value="{@id}">
                                            <xsl:if test="@id=../@page">
                                                <xsl:attribute name="selected">
                                                    <xsl:text>selected</xsl:text>
                                                </xsl:attribute>
                                            </xsl:if>
                                            <xsl:value-of select="@title"/>
                                        </option>
                                    </xsl:for-each>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="submit" class="btn btn-primary" value="{$locale/cms/adm/menuitem/submit}"/>
                        </div>
                    </form>
                </xsl:when>
                <xsl:otherwise>
                    <span class="message">
                        <xsl:value-of select="$locale/cms/adm/menuitem/no-pages"/>
                    </span>
                </xsl:otherwise>
            </xsl:choose>
        </div>
        <!-- Add/edit link -->
        <div id="linkForm" style="display:none" class="menuItemForm">
            <form action="/adm/content/menu/savelink" method="post" class="ajaxer">
                <input type="hidden" name="menu" value="{@menu}"/>
                <input type="hidden" name="id" value="{@id}"/>
                <xsl:if test="name()='CMSMenuItemEdit' and not(@page) and @link">
                    <script type="text/javascript">cms.switchItemForm('link');</script>
                </xsl:if>
                <div class="form-group row">
                    <label for="cms-menuitem-link-label" class="col-sm-2 col-form-label">
                        <xsl:value-of select="$locale/cms/adm/menuitem/link-label"/>
                    </label>
                    <div class="col-sm-10">
                        <input type="text" name="label" class="form-control" value="{@label}" id="cms-menuitem-link-label"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="cms-menuitem-link" class="col-sm-2 col-form-label">
                        <xsl:value-of select="$locale/cms/adm/menuitem/type-link"/>
                    </label>
                    <div class="col-sm-10">
                        <input type="text" name="link" class="form-control" value="{@link}" id="cms-menuitem-link"/>
                    </div>
                </div>
                <div class="form-group row">
                    <input type="submit" class="btn btn-primary" value="{$locale/cms/adm/menuitem/submit}"/>
                </div>
            </form>
        </div>
        <!-- Add/edit empty item -->
        <div id="emptyForm" style="display:none" class="menuItemForm">
            <form action="/adm/content/menu/saveempty" method="post" class="ajaxer">
                <input type="hidden" name="menu" value="{@menu}"/>
                <input type="hidden" name="id" value="{@id}"/>
                <xsl:if test="name()='CMSMenuItemEdit' and not(@page) and not(@link)">
                    <script type="text/javascript">cms.switchItemForm('empty');</script>
                </xsl:if>
                <div class="form-group row">
                    <label for="cms-menuitem-empty-label" class="col-sm-2 col-form-label">
                        <xsl:value-of select="$locale/cms/adm/menuitem/link-label"/>
                    </label>
                    <div class="col-sm-10">
                        <input type="text" name="label" class="form-control" value="{@label}" id="cms-menuitem-empty-label"/>
                    </div>
                </div>
                <div class="form-group row">
                    <input type="submit" class="btn btn-primary" value="{$locale/cms/adm/menuitem/submit}"/>
                </div>
            </form>
        </div>
    </xsl:template>

    <xsl:template name="CMSMenuItemParentSelect">
        <div class="form-group row">
            <label for="cms-menuitem-parent" class="col-sm-2 col-form-label">Parent</label>
            <div class="col-sm-10">
                <select name="parent" onchange="$('input[name=\'parent\']').val($(this).val())" class="form-control">
                    <option value="0">—</option>
                    <xsl:call-template name="CMSMenuItemParentOption"/>
                </select>
            </div>
        </div>
    </xsl:template>

    <xsl:template name="CMSMenuItemParentOption">
        <xsl:param name="nodeSet" select="parents"/>
        <xsl:param name="parent" select="''"/>
        <xsl:param name="depth" select="0"/>
        <xsl:param name="depthSpace" select="''"/>
        <xsl:for-each select="$nodeSet/menuitem[@parent=$parent]">
            <xsl:if test="not($nodeSet/../@id) or not(@id=$nodeSet/../@id)">
                <option value="{@id}">
                    <xsl:if test="@id=$nodeSet/../@parent">
                        <xsl:attribute name="selected">selected</xsl:attribute>
                    </xsl:if>
                    <xsl:value-of select="$depthSpace"/>
                    <xsl:value-of select="@label"/>
                </option>
                <xsl:if test="$depth+1&lt;number($nodeSet/@depth)">
                    <xsl:call-template name="CMSMenuItemParentOption">
                        <xsl:with-param name="nodeSet" select="$nodeSet"/>
                        <xsl:with-param name="parent" select="@id"/>
                        <xsl:with-param name="depth" select="$depth+1"/>
                        <xsl:with-param name="depthSpace" select="concat('&#160;&#160;',$depthSpace)"/>
                    </xsl:call-template>
                </xsl:if>
            </xsl:if>
        </xsl:for-each>
    </xsl:template>
</xsl:stylesheet>
