<?xml version="1.0"?>
<xsl:stylesheet
        xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
        xmlns:mml="http://www.w3.org/1998/Math/MathML"
        exclude-result-prefixes="mml"
        version="1.0">
    <xsl:template match="mml:math" >
        <xsl:choose>
            <xsl:when test="mml:mtable[@class='eqnarray']
            or  mml:mtable[@class='eqnarray-star']   ">
                <xsl:text>@@backslash@@empty</xsl:text>
                <xsl:apply-templates />
                <xsl:text>@@backslash@@empty</xsl:text>
            </xsl:when>
            <xsl:otherwise>
                <xsl:if test="@display='block'"><xsl:text>@@dollar@@</xsl:text></xsl:if>
                <xsl:text>@@dollar@@</xsl:text>
                <xsl:apply-templates/>
                <xsl:if test="@display='block'"><xsl:text>@@dollar@@</xsl:text></xsl:if>
                <xsl:text>@@dollar@@</xsl:text>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="mml:mrow" >
        <xsl:text>@@lbrace@@</xsl:text>
        <xsl:apply-templates/>
        <xsl:text>@@rbrace@@</xsl:text>
    </xsl:template>

    <xsl:template match="mml:mrow[@class='mbox']" >
        <xsl:text>@@backslash@@mbox@@lbrace@@</xsl:text>
        <xsl:apply-templates select="mml:mtext"/>
        <xsl:text>@@rbrace@@</xsl:text>
    </xsl:template>

    <xsl:template match="mml:msup" >
        <xsl:call-template name="base-scription" />
<xsl:text>@@sp@@
  @@lbrace@@</xsl:text>
        <xsl:apply-templates select="*[2]"/>
        <xsl:text>@@rbrace@@</xsl:text>
    </xsl:template>

    <xsl:template match="mml:msub" >
        <xsl:call-template name="base-scription" />
<xsl:text>@@sb@@
  @@lbrace@@</xsl:text>
        <xsl:apply-templates select="*[2]"/>
        <xsl:text>@@rbrace@@</xsl:text>
    </xsl:template>

    <xsl:template match="mml:msubsup" >
        <xsl:call-template name="base-scription" />
<xsl:text>@@sb@@
  @@lbrace@@</xsl:text>
        <xsl:apply-templates select="*[2]"/>
<xsl:text>@@rbrace@@@@sp@@
  @@lbrace@@</xsl:text>
        <xsl:apply-templates select="*[3]"/>
        <xsl:text>@@rbrace@@</xsl:text>
    </xsl:template>

    <xsl:template name="base-scription">
        <xsl:choose>
            <xsl:when test="child::*[position()=1 and
   self::*[string-length(.)=1 and position()=1 and position()=last()]]">
                <xsl:apply-templates select="*[1]"/>
            </xsl:when>
            <xsl:otherwise>
                <xsl:text>@@lbrace@@</xsl:text>
                <xsl:apply-templates select="*[1]"/>
                <xsl:text>@@rbrace@@</xsl:text>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="mml:mfrac" >
 <xsl:text>@@backslash@@frac
 </xsl:text>
        <xsl:for-each select="*">
            <xsl:text>@@lbrace@@</xsl:text>
            <xsl:apply-templates/>
            <xsl:text>@@rbrace@@</xsl:text>
        </xsl:for-each>
    </xsl:template>

    <xsl:template match="mml:mi[@class='mathrm']">
        <xsl:text>@@lbrace@@@@backslash@@mathrm@@lbrace@@</xsl:text>
        <xsl:apply-templates/>
        <xsl:text>@@rbrace@@@@rbrace@@</xsl:text>
    </xsl:template>
    <xsl:template match="mml:mo[@class='csname']">
        <xsl:text>@@backslash@@</xsl:text>
        <xsl:value-of select="."/>
        <xsl:text> </xsl:text>
    </xsl:template>

    <xsl:template match="mml:mtable[@class='eqnarray']">
        <xsl:text>@@backslash@@begin@@lbrace@@eqnarray@@rbrace@@</xsl:text>
        <xsl:apply-templates select="mml:mtr" mode="eqnarray"/>
        <xsl:text>@@backslash@@end@@lbrace@@eqnarray@@rbrace@@</xsl:text>
    </xsl:template>

    <xsl:template match="mml:mtable[@class='eqnarray-star']">
        <xsl:text>@@backslash@@begin@@lbrace@@eqnarray*@@rbrace@@</xsl:text>
        <xsl:apply-templates select="mml:mtr" mode="eqnarray"/>
        <xsl:text>@@backslash@@end@@lbrace@@eqnarray*@@rbrace@@</xsl:text>
    </xsl:template>

    <xsl:template match="mml:mtr" mode="eqnarray">
        <xsl:apply-templates select="mml:mtd" mode="eqnarray"/>
        <xsl:if test="position()!=last()">
     <xsl:text>@@backslash@@@@backslash@@
     </xsl:text>
        </xsl:if>
    </xsl:template>

    <xsl:template match="mml:mtd" mode="eqnarray">
        <xsl:if test="position()!=1">
     <xsl:text>@@ampersand@@@@comment@@
     </xsl:text>
        </xsl:if>
        <xsl:apply-templates/>
    </xsl:template>

    <xsl:template match="mml:mtd[@class='eqnarray-4']" mode="eqnarray">
        <xsl:if test="child::mml:mtext[.='' and @class='eqnarray']">
            <xsl:text>@@backslash@@nonumber </xsl:text>
        </xsl:if>
        <xsl:apply-templates select="mml:mtext[@class='label']" />
    </xsl:template>

    <xsl:template match="mml:mfenced">
        <xsl:choose>
            <xsl:when test="@open">
                <xsl:value-of select="concat('@@backslash@@left',@open)"/>
            </xsl:when>
            <xsl:otherwise> <xsl:text>@@backslash@@left.</xsl:text>
            </xsl:otherwise>
        </xsl:choose>
        <xsl:apply-templates/>
        <xsl:choose>
            <xsl:when test="@close">
                <xsl:value-of select="concat('@@backslash@@right',@close)"/>
            </xsl:when>
            <xsl:otherwise> <xsl:text>@@backslash@@right.</xsl:text>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="mml:mtable[@class='array']">
        <xsl:text>@@backslash@@begin@@lbrace@@array@@rbrace@@@@lbrace@@</xsl:text>
        <xsl:value-of select="comment()[last()]"/>
        <xsl:text>@@rbrace@@</xsl:text>
        <xsl:apply-templates select="mml:mtr" mode="array"/>
        <xsl:text>@@backslash@@end@@lbrace@@array@@rbrace@@</xsl:text>
    </xsl:template>

    <xsl:template match="mml:mtr" mode="array">
        <xsl:apply-templates select="mml:mtd" mode="array"/>
        <xsl:if test="position()!=last()">
     <xsl:text>@@backslash@@@@backslash@@
     </xsl:text>
        </xsl:if>
    </xsl:template>

    <xsl:template match="mml:mtd" mode="array">
        <xsl:if test="position()!=1">
     <xsl:text>@@ampersand@@@@comment@@
     </xsl:text>
        </xsl:if>
        <xsl:apply-templates/>
    </xsl:template>

    <xsl:template match="mml:munderover[@accent='true']">
        <xsl:choose>
            <xsl:when test="
   child::mml:mrow[2]= ''
 and
   child::mml:mrow[position()=3 and child::mml:mo='&#x0304;']
 ">
                <xsl:text>@@backslash@@bar@@lbrace@@</xsl:text>
                <xsl:apply-templates select="*[1]" />
                <xsl:text>@@rbrace@@</xsl:text>
            </xsl:when>
            <xsl:otherwise>
                <xsl:apply-templates />
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="mml:mspace[@width]">
        <xsl:choose>
            <xsl:when  test="@width!='0pt' and @width!='0.0pt'">
                <xsl:text>@@backslash@@hspace@@lbrace@@</xsl:text>
                <xsl:value-of select="@width"/>
                <xsl:text>@@rbrace@@</xsl:text>
            </xsl:when>
            <xsl:when test="@class='label'">
                <xsl:apply-templates select="." mode="mspace-label" />
            </xsl:when>

            <xsl:otherwise>
                <xsl:text>@@backslash@@empty </xsl:text>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
    <xsl:template match="mml:mspace[@class='nbsp']">
        <xsl:text>@@backslash@@  </xsl:text>
    </xsl:template>
    <xsl:template match="mml:mspace" mode="mspace-label">
        <xsl:text>@@backslash@@empty </xsl:text>
    </xsl:template>

    <xsl:template match="mml:msqrt">
        <xsl:text>@@backslash@@sqrt@@lbrace@@</xsl:text>
        <xsl:apply-templates />
        <xsl:text>@@rbrace@@</xsl:text>
    </xsl:template>

    <xsl:template match="mml:mtext[@class='label']">
        <xsl:text>@@backslash@@label@@lbrace@@</xsl:text>
        <xsl:value-of select="@id" />
        <xsl:text>@@rbrace@@</xsl:text>
    </xsl:template>
    <xsl:template match="mml:mtext[@class='endlabal']
                  | mml:mspace[@class='endlabal']">
    </xsl:template>

</xsl:stylesheet>