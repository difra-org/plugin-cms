<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" version="1.0">

	<xsl:template match="CMSList">
		<h2>
			<xsl:value-of select="$locale/cms/adm/h2"/>
		</h2>
		<a href="/adm/content/pages/add" class="btn btn-primary fas fa-plus" title="{$locale/cms/adm/actions/add}"/>
		<br/>
		<br/>

		<xsl:choose>
			<xsl:when test="page">
				<table class="table table-striped">
					<thead class="thead-dark">
						<tr>
							<th>
								<xsl:value-of select="$locale/cms/adm/name"/>
							</th>
							<th>
								<xsl:value-of select="$locale/cms/adm/uri"/>
							</th>
							<th>
								<xsl:value-of select="$locale/cms/adm/hidden"/>
							</th>
							<th/>
						</tr>
					</thead>
					<tbody>
						<xsl:apply-templates select="page"/>
					</tbody>
				</table>
			</xsl:when>
			<xsl:otherwise>
				<div class="alert alert-info">
					<xsl:value-of select="$locale/cms/adm/no-pages"/>
				</div>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="page">
		<tr>
			<td>
				<xsl:value-of select="@title"/>
			</td>
			<td>
				<xsl:value-of select="@uri"/>
			</td>
			<td>
				<xsl:choose>
					<xsl:when test="@hidden=1">
						<xsl:value-of select="$locale/cms/adm/hidden-flag"/>
					</xsl:when>
					<xsl:otherwise>
						<xsl:text>—</xsl:text>
					</xsl:otherwise>
				</xsl:choose>
			</td>
			<td class="actions">
				<a href="{@uri}" target="_blank" class="btn btn-dark fas fa-eye" title="{$locale/cms/adm/actions/view}"/>
				<xsl:text> </xsl:text>
				<a href="/adm/content/pages/edit/{@id}" class="btn btn-dark fas fa-edit" title="{$locale/cms/adm/actions/edit}"/>
				<xsl:text> </xsl:text>
				<a href="/adm/content/pages/delete/{@id}" class="btn btn-danger fas fa-trash-alt ajaxer" title="{$locale/cms/adm/actions/delete}"/>
			</td>
		</tr>
	</xsl:template>
</xsl:stylesheet>
