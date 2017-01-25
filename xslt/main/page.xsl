<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" version="1.0">
	<xsl:template match="page">
		<div class="page">
			<xsl:value-of disable-output-escaping="yes" select="@body"/>
		</div>
	</xsl:template>
</xsl:stylesheet>
