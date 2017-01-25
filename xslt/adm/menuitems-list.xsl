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
        <a href="/adm/content/menu/add/{@id}" class="button">
            <xsl:value-of select="$locale/cms/adm/items/new"/>
        </a>

        <xsl:choose>
            <xsl:when test="menuitem">
                <table>
                    <colgroup>
                        <col/>
                        <col style="width: 220px"/>
                        <col style="width: 220px"/>
                        <col style="width: 130px"/>
                    </colgroup>
                    <thead>
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
                <span class="message">
                    <xsl:value-of select="$locale/cms/adm/items/empty"/>
                </span>
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
                            <a href="/adm/content/menu/add/{$nodeSet/@id}/parent/{@id}" class="action add"/>
                        </xsl:when>
                        <xsl:otherwise>
                            <a href="#" class="action add disabled"/>
                        </xsl:otherwise>
                    </xsl:choose>
                    <a href="/adm/content/menu/up/{@id}" class="action up ajaxer">
                        <xsl:if test="position()=1">
                            <xsl:attribute name="class">action up ajaxer disabled</xsl:attribute>
                        </xsl:if>
                    </a>
                    <a href="/adm/content/menu/down/{@id}" class="action down ajaxer">
                        <xsl:if test="position()=last()">
                            <xsl:attribute name="class">action down ajaxer disabled</xsl:attribute>
                        </xsl:if>
                    </a>
                    <a href="/adm/content/menu/delete/{@id}" class="action delete ajaxer"/>
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
