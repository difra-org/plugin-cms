<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" version="1.0">
    <xsl:template match="CMSMenu">
        <xsl:call-template name="CMS-SubMenu"/>
    </xsl:template>

    <xsl:template name="CMS-SubMenu">
        <xsl:param name="parent" select="''"/>
        <xsl:param name="nodeset" select="."/>
        <xsl:if test="count($nodeset/menuitem[@parent=$parent])>0">
            <ul>
                <xsl:if test="$nodeset/@parent=''">
                    <xsl:attribute name="id">
                        <xsl:text>menu_</xsl:text>
                        <xsl:value-of select="$nodeset/@name"/>
                    </xsl:attribute>
                </xsl:if>
                <xsl:for-each select="$nodeset/menuitem[@parent=$parent]">
                    <li>
                        <xsl:variable name="id" select="@id"/>
                        <xsl:if test="@match or count($nodeset/menuitem[@parent=$id])>0">
                            <xsl:attribute name="class">
                                <xsl:if test="@match">
                                    <xsl:text>match </xsl:text>
                                    <xsl:value-of select="@match"/>
                                    <xsl:text> </xsl:text>
                                </xsl:if>
                                <xsl:if test="count($nodeset/menuitem[@parent=$id])>0">
                                    <xsl:text>has-children</xsl:text>
                                </xsl:if>
                            </xsl:attribute>
                        </xsl:if>
                        <xsl:choose>
                            <xsl:when test="page">
                                <a href="{page/@uri}">
                                    <xsl:value-of select="@label"/>
                                </a>
                            </xsl:when>
                            <xsl:when test="@link">
                                <a href="{@link}">
                                    <xsl:value-of select="@label"/>
                                </a>
                            </xsl:when>
                            <xsl:otherwise>
                                <a href="#">
                                    <xsl:value-of select="@label"/>
                                </a>
                            </xsl:otherwise>
                        </xsl:choose>
                        <xsl:call-template name="CMS-SubMenu">
                            <xsl:with-param name="parent" select="@id"/>
                            <xsl:with-param name="nodeset" select=".."/>
                        </xsl:call-template>
                    </li>
                </xsl:for-each>
            </ul>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>
