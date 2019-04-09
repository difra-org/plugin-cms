<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" version="1.0">
	<xsl:template match="CMSMenuList">
		<h2>
			<xsl:value-of select="$locale/cms/adm/menu/h2"/>
		</h2>

		<xsl:choose>
			<xsl:when test="menuobj">
				<table class="table table-striped">
					<thead class="thead-dark">
						<tr>
							<th>
								<xsl:value-of select="$locale/cms/adm/menu/name"/>
							</th>
							<th>
								<xsl:value-of
									select="$locale/cms/adm/menu/description"/>
							</th>
						</tr>
					</thead>
					<tbody>
						<xsl:apply-templates select="menuobj"/>
					</tbody>
				</table>
			</xsl:when>
			<xsl:otherwise>
				<span class="message">
					<xsl:value-of select="$locale/cms/adm/menu/empty"/>
				</span>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="menuobj">
		<tr>
			<td>
				<a href="/adm/content/menu/view/{@id}">
					<xsl:value-of select="@name"/>
				</a>
			</td>
			<td>
				<xsl:value-of select="@description"/>
			</td>
		</tr>
	</xsl:template>
</xsl:stylesheet>
