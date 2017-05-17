<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:template match="/">

    <html>
      <head>
        <title>Energy drinks</title>
        <link rel="stylesheet" href="../css/styles.css" type="text/css" />
      </head>

      <body>
        <div class="main-wrap">

          <!-- Navigation menu -->
          
          <header class="logo-wrap">
            <div id="logo"><img src="../images/logo.png" title="Home page" alt="Home page" /></div>
            <ul id="navigation">
              <li><a href="../index.html">Home</a></li>
              <li><a href="../xml-xsl/soft-drinks.xml">Soft drinks</a></li>
              <li><a href="../xml-xsl/energy-drinks.xml">Energy drinks</a></li>
              <li><a href="../xml-xsl/juices.xml">Juices</a></li>
            </ul>
          </header>

          <!-- Table for each product -->

          <div class="products">
            <xsl:for-each select="products/product_item">
              <table>
                <tr>
                  <th><xsl:value-of select="product_name"/></th>
                </tr>
                <tr>
                  <td class="container">
                    <img>
                      <xsl:attribute name="class">product-image</xsl:attribute>
                      <xsl:attribute name="src"><xsl:value-of select="image/url"/></xsl:attribute>
                      <xsl:attribute name="alt"><xsl:value-of select="image/alt"/></xsl:attribute>
                      <xsl:attribute name="title"><xsl:value-of select="image/title"/></xsl:attribute>
                    </img>
                    <div>Company name: <xsl:value-of select="company_name"/></div>
                    <div><span>Fats: </span><span><xsl:value-of select="nutritional_content/fats"/></span></div>
                    <div><span>Carbohydrates: </span><span><xsl:value-of select="nutritional_content/carbohydrates"/></span></div>
                    <div><span>Protein: </span><span><xsl:value-of select="nutritional_content/protein"/></span></div>
                    <div>Ingredients: <xsl:value-of select="ingredients"/></div>
                    <div>Links:
                      <xsl:for-each select="link">
                        <a>
                          <xsl:attribute name="class">external</xsl:attribute>
                          <xsl:attribute name="href"><xsl:value-of select="url"/></xsl:attribute>
                          <xsl:text>Link </xsl:text><xsl:value-of select="position()"/><xsl:text> </xsl:text>
                        </a>
                      </xsl:for-each>
                    </div>
                    <div>Facts: <xsl:value-of select="facts"/></div>
                  </td>
                </tr>
              </table>
            </xsl:for-each>
          </div>
        
        </div>
      </body>
    </html>

  </xsl:template>
</xsl:stylesheet>
