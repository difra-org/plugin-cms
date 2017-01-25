<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" version="1.0">
	<xsl:template match="snippetList">
		<h2>
			<xsl:value-of select="$locale/cms/adm/snippet/title"/>
		</h2>
		<xsl:if test="/root/@debug=1">
			<a href="/adm/content/snippets/add" class="action add">
				<xsl:value-of select="$locale/adm/actions/add"/>
			</a>
		</xsl:if>
		<xsl:choose>
			<xsl:when test="snippet">
				<table>
					<tr>
						<xsl:if test="/root/@debug=1">
							<th>
								<xsl:value-of select="$locale/cms/adm/snippet/name"/>
							</th>
						</xsl:if>
						<th>
							<xsl:value-of select="$locale/cms/adm/snippet/description"/>
						</th>
						<th>
						</th>
					</tr>
					<xsl:apply-templates select="snippet" mode="adm"/>
				</table>
			</xsl:when>
			<xsl:otherwise>
				<span class="message">
					<xsl:value-of select="$locale/cms/adm/snippet/no-snippet"/>
				</span>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="snippet" mode="adm">
		<tr>
			<xsl:if test="/root/@debug=1">
				<td>
					<xsl:value-of select="@name"/>
				</td>
			</xsl:if>
			<td>
				<xsl:value-of select="@description"/>
			</td>
			<td class="actions">
				<a href="/adm/content/snippets/edit/{@id}" class="action edit"/>
				<xsl:if test="/root/@debug=1">
					<a href="/adm/content/snippets/del/{@id}" class="action delete ajaxer"/>
				</xsl:if>
			</td>
		</tr>
	</xsl:template>
</xsl:stylesheet>