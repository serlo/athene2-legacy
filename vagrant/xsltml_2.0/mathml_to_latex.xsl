<?xml version="1.0" encoding="iso-8859-1"?>

<!--
FILE : mathml_to_latex.xsl

CREATED : 19 November 2001

LAST MODIFIED : 4 December 2003

AUTHOR : Autumn A. Cuellar (a.cuellar@auckland.ac.nz)
         Department of Engineering Science
         The University of Auckland

DESCRIPTION : This stylesheet converts the MathML in CellML models to Latex.

CHANGES :
  2002/02/18 - AAC - Changed the minus element from an n-ary operator to a
                     binary operator.
  2002/03/14 - AAC - Changed the piecewise if statement so that the whole
                     statement is not text.
  2002/06/06 - WJH - Fixed a bug where only <component> elements that contained
                     a <math> element at the top level were included in the
                     output.
  2002/08/16 - AAC - Now every component is read, not just the ones containing
                     mathematics.  Put quotes around component name and moved
                     the calculation names so that they come before the
                     calculation.
  2003/01/20 - AAC - Added rendering of mathml:matrix and mathml:partialdiff.
  2003/09/09 - AAC - Expanded margins, turned to landscape.  Added rendering of 
                     mathml:equivalant and mathml:sum and of variables with 
                     @type of 'fn'.  Script now checks for any mathml:elements 
                     it doesn't recognize, and prints out message in red if 
                     there are any.
  2003/12/04 - AAC - Put spaces after the LaTeX \partial command. Not sure why 
                     it was working before.
-->

<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:cellml="http://www.cellml.org/cellml/1.0#"
    xmlns:mathml="http://www.w3.org/1998/Math/MathML"
    version="1.0">

  <xsl:import href="latex_utilities_frag.xsl" />
  
  <xsl:param name="INPUT_FILE"          select="'.'" />
  
  <xsl:output method="text" />

  <xsl:template match="cellml:model">
    <xsl:text>\documentclass[10pt,landscape]{article}
\usepackage{a4}
\usepackage{amsmath}
\usepackage{color}
\setlength{\hoffset}{-1.0in}
\addtolength{\textwidth}{1.0in}
\addtolength{\topmargin}{-0.875in}
\addtolength{\textheight}{-2.1in}

\title{</xsl:text>
    <xsl:call-template name="latex_util_escape_special_characters">
      <xsl:with-param name="input_text">
        <xsl:value-of select="@name" />
      </xsl:with-param>
    </xsl:call-template>
    <xsl:text>}
\date{}
\begin{document}  
\maketitle
\newcommand{\ud}{\mathrm{d}}</xsl:text>
    <xsl:apply-templates select="cellml:component" />
    <xsl:text>
\end{document}</xsl:text>
  </xsl:template>

  <xsl:template match="cellml:component">
    <xsl:text>
\section{``</xsl:text>
    <xsl:call-template name="latex_util_escape_special_characters">
      <xsl:with-param name="input_text">
        <xsl:value-of select="@name" />
      </xsl:with-param>
    </xsl:call-template>
    <xsl:text>'' component}</xsl:text>
    <xsl:choose>
      <xsl:when test="./mathml:math"><!--when component has a child math element-->
        <xsl:apply-templates select="./mathml:math" />
      </xsl:when>
      <xsl:otherwise>
        <xsl:text>
This component has no equations.</xsl:text>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <xsl:template match="mathml:math">
    <xsl:for-each select="mathml:apply">
      <xsl:text>
\textbf{</xsl:text>
      <xsl:call-template name="latex_util_escape_special_characters">
        <xsl:with-param name="input_text">
          <xsl:apply-templates select="." mode="caption" />
        </xsl:with-param>
      </xsl:call-template>
      <xsl:text>}
\begin{displaymath}</xsl:text>
      <xsl:apply-templates select="." />
      <xsl:text>
\end{displaymath}</xsl:text>
    </xsl:for-each>
  </xsl:template>

  <xsl:template name="one_sibling">
    <xsl:if test="count(following-sibling::*) > 1">
      <xsl:call-template name="error_message" />
    </xsl:if>
  </xsl:template>

  <xsl:template name="two_siblings">
    <xsl:if test="count(following-sibling::*) > 2">
      <xsl:call-template name="error_message" />
    </xsl:if>
  </xsl:template>

  <xsl:template name="error_message">
    <xsl:text>
 {\color[rgb]{1,0,0}***ERROR*** }</xsl:text>
  </xsl:template>

  <xsl:template name="choices">
    <xsl:apply-templates select="mathml:cn" />
    <xsl:apply-templates select="mathml:ci" />
    <xsl:apply-templates select="mathml:apply" />
    <xsl:apply-templates select="mathml:piecewise" />
    <xsl:apply-templates select="mathml:matrix" />
  </xsl:template>

  <xsl:template match="mathml:apply" mode="caption">
    <xsl:if test="@id">
      <xsl:value-of select="@id" />
    </xsl:if>
  </xsl:template>  

  <xsl:template match="mathml:apply">
    <xsl:apply-templates select="child::*[1]" />
    <!--<xsl:apply-templates select="mathml:sum" />-->
  </xsl:template>

  <xsl:template match="mathml:piecewise">
    <xsl:text>
\begin{cases}</xsl:text>
    <xsl:if test="not(mathml:otherwise)">
      <xsl:for-each select="mathml:piece">
        <xsl:if test="position()!=last()">
          <xsl:apply-templates select="child::*[1]" />
          <xsl:text>&amp;  \text{if }</xsl:text>
          <xsl:apply-templates select="child::*[2]" />
          <xsl:text>,\\</xsl:text>
        </xsl:if>
        <xsl:if test="position()=last()">
          <xsl:apply-templates select="child::*[1]" />
          <xsl:text>&amp;  \text{if }</xsl:text>
          <xsl:apply-templates select="child::*[2]" />
          <xsl:text>.</xsl:text>
        </xsl:if>
      </xsl:for-each>
    </xsl:if>
    <xsl:if test="mathml:otherwise">
      <xsl:for-each select="mathml:piece">
        <xsl:apply-templates select="child::*[1]" />;
        <xsl:text>&amp;  \text{if }</xsl:text>
        <xsl:apply-templates select="child::*[2]" />
        <xsl:text>,\\</xsl:text>
      </xsl:for-each>    
      <xsl:apply-templates select="mathml:otherwise" />
    </xsl:if>
    <xsl:text>
\end{cases}</xsl:text>
  </xsl:template>

  <xsl:template match="mathml:otherwise">
    <xsl:call-template name="choices" />
    <xsl:text>&amp;  \text{otherwise}.</xsl:text>
  </xsl:template>

  <xsl:template match="mathml:matrix">
    <xsl:text>\begin{vmatrix}</xsl:text>
    <xsl:for-each select="mathml:matrixrow">
      <xsl:if test="position()!=last()">
        <xsl:for-each select="*">
          <xsl:if test="position()!=last()">
            <xsl:apply-templates select="." />
            <xsl:text> &amp; </xsl:text>
          </xsl:if>
          <xsl:if test="position()=last()">
            <xsl:apply-templates select="." />
            <xsl:text> \\ </xsl:text>
          </xsl:if>
        </xsl:for-each>
      </xsl:if>
      <xsl:if test="position()=last()">
        <xsl:for-each select="child::*">
          <xsl:if test="position()!=last()">
            <xsl:apply-templates select="." />
            <xsl:text> &amp; </xsl:text>
          </xsl:if>
          <xsl:if test="position()=last()">
            <xsl:apply-templates select="." />
            <xsl:text> \end{vmatrix}\\ </xsl:text>
          </xsl:if>
        </xsl:for-each>
      </xsl:if>
    </xsl:for-each>
  </xsl:template>
    
  <xsl:template match="mathml:cn">
    <xsl:value-of select="." />
  </xsl:template>

  <xsl:template match="mathml:ci">
    <xsl:choose>
      <xsl:when test="@type='fn'">
        <xsl:call-template name="latex_util_escape_special_characters">
          <xsl:with-param name="input_text">
            <xsl:value-of select="." />
          </xsl:with-param>
        </xsl:call-template>
        <xsl:text>(</xsl:text>
        <xsl:apply-templates select="following-sibling::*[1]" />
        <xsl:text>)</xsl:text>
        <xsl:call-template name="one_sibling" />
      </xsl:when>
      <xsl:otherwise>
        <xsl:call-template name="latex_util_escape_special_characters">
          <xsl:with-param name="input_text">
            <xsl:value-of select="." />
          </xsl:with-param>
        </xsl:call-template>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <xsl:template match="mathml:eq">
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text> = </xsl:text>
    <xsl:apply-templates select="following-sibling::*[2]" />
    <xsl:call-template name="two_siblings" />
  </xsl:template>
    
  <xsl:template match="mathml:equivalent">
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text> \equiv </xsl:text>
    <xsl:apply-templates select="following-sibling::*[2]" />
    <xsl:call-template name="two_siblings" />
  </xsl:template>
    
  <xsl:template match="mathml:neq">
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text> \neq </xsl:text>
    <xsl:apply-templates select="following-sibling::*[2]" />
    <xsl:call-template name="two_siblings" />
  </xsl:template>

  <xsl:template match="mathml:gt">
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text> > </xsl:text> 
    <xsl:apply-templates select="following-sibling::*[2]" />
    <xsl:call-template name="two_siblings" />
  </xsl:template>

  <xsl:template match="mathml:lt">
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text> &lt; </xsl:text>
    <xsl:apply-templates select="following-sibling::*[2]" />
    <xsl:call-template name="two_siblings" />
  </xsl:template>

  <xsl:template match="mathml:geq">
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text> \geq </xsl:text>
    <xsl:apply-templates select="following-sibling::*[2]" />
    <xsl:call-template name="two_siblings" />
  </xsl:template>

  <xsl:template match="mathml:leq">
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text> \leq </xsl:text>
    <xsl:apply-templates select="following-sibling::*[2]" />
    <xsl:call-template name="two_siblings" />
  </xsl:template>

  <xsl:template match="mathml:plus">
    <xsl:text>\left(</xsl:text>
    <xsl:for-each select="following-sibling::*">
      <xsl:apply-templates select="." />
      <xsl:if test="following-sibling::*">
        <xsl:text>+</xsl:text>
      </xsl:if>
    </xsl:for-each>
    <xsl:text>\right)</xsl:text>
  </xsl:template>

  <xsl:template match="mathml:minus">
    <xsl:if test="count(following-sibling::*) = 1">
      <xsl:text>-(</xsl:text>
      <xsl:apply-templates select="following-sibling::*[1]" />
      <xsl:text>)</xsl:text>
    </xsl:if>
    <xsl:if test="count(following-sibling::*) = 2">
      <xsl:text>\left(</xsl:text>
      <xsl:apply-templates select="following-sibling::*[1]" />
      <xsl:text>-</xsl:text>
      <xsl:apply-templates select="following-sibling::*[2]" />
      <xsl:text>\right)</xsl:text>
    </xsl:if>
    <xsl:call-template name="two_siblings" />
  </xsl:template>

  <xsl:template match="mathml:times">
    <xsl:for-each select="following-sibling::*">
      <xsl:apply-templates select="." /> 
      <xsl:if test="following-sibling::*">
        <xsl:text> \ast </xsl:text>
      </xsl:if>
    </xsl:for-each>
  </xsl:template>

  <xsl:template match="mathml:divide">
    <xsl:text>\frac{</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>}{</xsl:text>
    <xsl:apply-templates select="following-sibling::*[2]" />
    <xsl:text>}</xsl:text>
    <xsl:call-template name="two_siblings" />
  </xsl:template>

  <xsl:template match="mathml:power">
    <xsl:text>\left(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />    
    <xsl:text>\right)^{</xsl:text>
    <xsl:apply-templates select="following-sibling::*[2]" />
    <xsl:text>}</xsl:text>
    <xsl:call-template name="two_siblings" />
  </xsl:template>

  <xsl:template match="mathml:root">
    <xsl:text>\sqrt</xsl:text>
    <xsl:if test="following-sibling='mathml:degree'">
      <xsl:text>[</xsl:text>
      <xsl:value-of select="following-sibling::*[1]" />
      <xsl:text>]</xsl:text>
    </xsl:if>
    <xsl:text>{</xsl:text>
    <xsl:apply-templates select="following-sibling::*" />
    <xsl:text>}</xsl:text>
    <xsl:call-template name="two_siblings" />
  </xsl:template>

  <xsl:template match="mathml:abs">
    <xsl:text>\arrowvert</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>\arrowvert</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:exp">
    <xsl:text>e^{</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>}</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:ln">
    <xsl:text>\ln{</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>}</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:log">
    <xsl:text>\log</xsl:text>
    <xsl:if test="following-sibling='mathml:logbase'">
      <xsl:text>_{</xsl:text>
      <xsl:apply-templates select="mathml:logbase" />
      <xsl:text>}</xsl:text>
    </xsl:if>
    <xsl:text>\left(</xsl:text>
    <xsl:apply-templates select="following-sibling::*" />
    <xsl:text>\right)</xsl:text>
    <xsl:call-template name="two_siblings" />
  </xsl:template>

  <xsl:template match="mathml:floor">
    <xsl:text>floor\left(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>\right)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:ceiling">
    <xsl:text>ceiling\left(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>\right)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:factorial">
    <xsl:text>(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)!</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:and">
    <xsl:text>(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)\land(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[2]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="two_siblings" />
  </xsl:template>

  <xsl:template match="mathml:or">
    <xsl:text>(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)\lor(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[2]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="two_siblings" />
  </xsl:template>

  <xsl:template match="mathml:xor">
    <xsl:text>(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />    
    <xsl:text>)\oplus(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[2]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="two_siblings" />
  </xsl:template>

  <xsl:template match="mathml:not">
    <xsl:text>!(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:diff">
    <xsl:text>\frac{\ud(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[2]" />
    <xsl:text>)}{\ud(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)}</xsl:text>
    <xsl:call-template name="two_siblings" />
  </xsl:template>

  <xsl:template match="mathml:partialdiff">
    <xsl:text>\frac{\partial </xsl:text>
    <xsl:if test="following-sibling::mathml:degree">
      <xsl:text>^{</xsl:text>
      <xsl:apply-templates select="following-sibling::mathml:degree" />
      <xsl:text>}</xsl:text>
    </xsl:if>
    <xsl:for-each select="following-sibling::mathml:*">
      <xsl:if test="local-name()!='degree' and local-name()!='bvar'">
        <xsl:apply-templates select="." />
      </xsl:if>
    </xsl:for-each>
    <xsl:text>}{</xsl:text>
    <xsl:for-each select="following-sibling::mathml:bvar">
      <xsl:text>\partial </xsl:text>
      <xsl:call-template name="choices" />
      <xsl:if test="mathml:degree">
        <xsl:text>^{</xsl:text>
        <xsl:apply-templates select="mathml:degree" />
        <xsl:text>}</xsl:text>
      </xsl:if>
    </xsl:for-each>
    <xsl:text>}</xsl:text>
  </xsl:template>

  <xsl:template match="mathml:degree">
    <xsl:text>[</xsl:text>
    <xsl:call-template name="choices" />
    <xsl:text>]</xsl:text>
  </xsl:template>

  <xsl:template match="mathml:bvar">
    <xsl:call-template name="choices" />
  </xsl:template>

  <xsl:template match="mathml:logbase">
    <xsl:call-template name="choices" />
  </xsl:template>

  <xsl:template match="mathml:sin">
    <xsl:text>\sin(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:cos">
    <xsl:text>\cos(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:tan">
    <xsl:text>\tan(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:sec">
    <xsl:text>\sec(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:csc">
    <xsl:text>\csc(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:cot">
    <xsl:text>\cot(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:sinh">
    <xsl:text>\sinh(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:cosh">
    <xsl:text>\cosh(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:tanh">
    <xsl:text>\tanh(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:sech">
    <xsl:text>\sech(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:csch">
    <xsl:text>\csch(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:coth">
    <xsl:text>\coth(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:arcsin">
    <xsl:text>\arcsin(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:arccos">
    <xsl:text>\arccos(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:arctan">
    <xsl:text>\arctan(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:arccosh">
    <xsl:text>\arccosh(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:arccot">
    <xsl:text>\arccot(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:arccoth">
    <xsl:text>\arccoth(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:arcsinh">
    <xsl:text>\arcsinh(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:arctanh">
    <xsl:text>\arctanh(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:arccsc">
    <xsl:text>\arccsc(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:arccsch">
    <xsl:text>\arccsch(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:arcsec">
    <xsl:text>\arcsec(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:archsech">
    <xsl:text>\arcsech(</xsl:text>
    <xsl:apply-templates select="following-sibling::*[1]" />
    <xsl:text>)</xsl:text>
    <xsl:call-template name="one_sibling" />
  </xsl:template>

  <xsl:template match="mathml:true">
    <xsl:text>true</xsl:text>
  </xsl:template>
  
  <xsl:template match="mathml:false">
    <xsl:text>false</xsl:text>
  </xsl:template>

  <xsl:template match="mathml:notanumber">
    <xsl:text>notanumber</xsl:text>
  </xsl:template>

  <xsl:template match="mathml:pi">
    <xsl:text>\pi</xsl:text>
  </xsl:template>

  <xsl:template match="mathml:infinity">
    <xsl:text>\infty</xsl:text>
  </xsl:template>

  <xsl:template match="mathml:exponentiale">
    <xsl:text>e</xsl:text>
  </xsl:template>

  <xsl:template match="mathml:sum">
    <xsl:choose>
      <xsl:when test="following-sibling::mathml:lowlimit">
        <xsl:choose>
          <xsl:when test="following-sibling::mathml:uplimit">
            <xsl:text>\overset{</xsl:text>
            <xsl:apply-templates select="following-sibling::mathml:uplimit/*" />
            <xsl:text>}{\sum_{</xsl:text>
            <xsl:apply-templates select="following-sibling::mathml:bvar" />
            <xsl:text>=</xsl:text>
            <xsl:apply-templates select="following-sibling::mathml:lowlimit/*" />
            <xsl:text>}}</xsl:text>
          </xsl:when>
          <xsl:otherwise>
            <xsl:text>\sum_{</xsl:text>
            <xsl:apply-templates select="following-sibling::mathml:bvar" />
            <xsl:text>=</xsl:text>
            <xsl:apply-templates select="following-sibling::mathml:lowlimit/*" />
            <xsl:text>}</xsl:text>
          </xsl:otherwise>
        </xsl:choose>
      </xsl:when>
      <xsl:when test="mathml:condition">  
        <xsl:text>\sum_{</xsl:text>
        <xsl:apply-templates select="mathml:condition/*" />
        <xsl:text>}</xsl:text>
      </xsl:when>
      <xsl:otherwise>
        <xsl:text>\sum\nolimits</xsl:text>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:apply-templates select="following-sibling::mathml:apply" />
  </xsl:template>

  <xsl:template match="mathml:*">
    <xsl:text>
   {\color[rgb]{1,0,0}\textrm{I don't know how to render this equation.} }</xsl:text>
  </xsl:template>
</xsl:stylesheet>

