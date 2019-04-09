<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" version="1.0">

    <xsl:template match="CMSMenuItems">
        <h2>
            <a href="/adm/content/menu">
                <xsl:value-of select="$locale/cms/adm/menu/h2"/>
            </a>
            <xsl:text> → </xsl:text>
            <xsl:value-of select="$locale/cms/adm/items/h2"/>
        </h2>
        <a href="/adm/content/menu/add/{@id}" class="btn btn-primary fas fa-plus" title="$locale/cms/adm/items/new"/>
        <br/>
        <br/>

        <xsl:choose>
            <xsl:when test="menuitem">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>
                                <xsl:value-of select="$locale/cms/adm/menuitem/title"/>
                            </th>
                            <th>
                                <xsl:value-of select="$locale/cms/adm/menuitem/type"/>
                            </th>
                            <th>
                                <xsl:value-of
                                        select="$locale/cms/adm/menuitem/content"/>
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:call-template name="MenuItems-List"/>
                    </tbody>
                </table>
            </xsl:when>
            <xsl:otherwise>
                <div class="alert alert-info">
                    <xsl:value-of select="$locale/cms/adm/items/empty"/>
                </div>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template name="MenuItems-List">
        <xsl:param name="nodeSet" select="."/>
        <xsl:param name="parent" select="''"/>
        <xsl:param name="depth" select="0"/>
        <xsl:for-each select="$nodeSet/menuitem[@parent=$parent]">
            <tr>
                <td style="padding-left:{20+$depth*40}px">
                    <xsl:value-of select="@label"/>
                </td>
                <td>
                    <xsl:choose>
                        <xsl:when test="@type='page'">
                            <xsl:value-of select="$locale/cms/adm/menuitem/type-page"/>
                        </xsl:when>
                        <xsl:when test="@type='link'">
                            <xsl:value-of select="$locale/cms/adm/menuitem/type-link"/>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:value-of select="$locale/cms/adm/menuitem/type-empty"/>
                        </xsl:otherwise>
                    </xsl:choose>
                </td>
                <td>
                    <xsl:value-of select="@link"/>
                </td>
                <td class="actions">
                    <a href="/adm/content/menu/edit/{@id}" class="action edit"/>
                    <xsl:choose>
                        <xsl:when test="$depth+1&lt;$nodeSet/@depth">
                            <a href="/adm/content/menu/add/{$nodeSet/@id}/parent/{@id}" class="btn btn-dark fas fa-plus" title="{$locale/cms/adm/actions/add}"/>
                        </xsl:when>
                        <xsl:otherwise>
                            <a href="#" class="btn btn-dark fas fa-plus disabled"/>
                        </xsl:otherwise>
                    </xsl:choose>
                    <xsl:text> </xsl:text>
                    <a href="/adm/content/menu/up/{@id}" title="{$locale/cms/adm/actions/up}">
                        <xsl:attribute name="class">
                            <xsl:text>btn btn-dark fas fa-caret-up ajaxer</xsl:text>
                            <xsl:if test="position()=1">
                                <xsl:text> disabled</xsl:text>
                            </xsl:if>
                        </xsl:attribute>
                    </a>
                    <xsl:text> </xsl:text>
                    <a href="/adm/content/menu/down/{@id}" title="{$locale/cms/adm/actions/down}">
                        <xsl:attribute name="class">
                            <xsl:text>btn btn-dark fas fa-caret-down ajaxer</xsl:text>
                            <xsl:if test="position()=last()">
                                <xsl:text> disabled</xsl:text>
                            </xsl:if>
                        </xsl:attribute>
                    </a>
                    <xsl:text> </xsl:text>
                    <a href="/adm/content/menu/delete/{@id}" class="btn btn-danger fas fa-trash-alt ajaxer" title="{$locale/cms/adm/actions/delete}"/>
                </td>
            </tr>
            <xsl:call-template name="MenuItems-List">
                <xsl:with-param name="parent" select="@id"/>
                <xsl:with-param name="depth" select="$depth+1"/>
                <xsl:with-param name="nodeSet" select=".."/>
            </xsl:call-template>
        </xsl:for-each>
    </xsl:template>
</xsl:stylesheet>
