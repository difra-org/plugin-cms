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
			<table class="form">
				<colgroup>
					<col style="width: 250px"/>
					<col/>
				</colgroup>
				<tbody>
					<tr>
						<th>
							<xsl:value-of select="$locale/cms/adm/title"/>
						</th>
						<td>
							<input type="text" class="full-width" name="title"
							       value="{@title}"/>
						</td>
					</tr>
					<tr>
						<th>
							<xsl:value-of select="$locale/cms/adm/tag"/>
						</th>
						<td>
							<input type="text" class="full-width" name="tag"
							       value="{@uri}"/>
						</td>
					</tr>
				</tbody>
			</table>
			<h3>
				<xsl:value-of select="$locale/cms/adm/body"/>
			</h3>
			<textarea name="body" editor="Full" bodyClass="page" cols="" rows="">
				<xsl:value-of select="@body"/>
			</textarea>
			<div class="form-buttons">
				<input type="submit" value="{$locale/cms/adm/submit}"/>
			</div>
		</form>
	</xsl:template>
</xsl:stylesheet>
