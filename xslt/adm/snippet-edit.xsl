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
			<table class="form">
				<colgroup>
					<col style="width: 250px"/>
					<col/>
				</colgroup>
				<tbody>
					<tr>
						<th>
							<xsl:value-of select="$locale/cms/adm/snippet/name"/>
						</th>
						<td>
							<input type="text" class="full-width" name="name"
							       value="{@name}"/>
						</td>
					</tr>
					<tr>
						<th>
							<xsl:value-of select="$locale/cms/adm/snippet/description"/>
						</th>
						<td>
							<input type="text" class="full-width" name="description"
							       value="{@description}"/>
						</td>
					</tr>
				</tbody>
			</table>
			<h3>
				<xsl:value-of select="$locale/cms/adm/snippet/text"/>
			</h3>
			<textarea rows="" cols="" name="text" editor="Full" bodyClass="page">
				<xsl:value-of select="."/>
			</textarea>
			<div class="form-buttons">
				<input type="submit" value="{$locale/adm/save}"/>
			</div>
		</form>
	</xsl:template>
</xsl:stylesheet>