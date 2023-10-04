import java.sql.*;
import java.util.ArrayList;
import java.util.List;

/**
 * Classe d'acc�s aux donn�es contenues dans la table article
 * @version 1.1
 * */
public class ArticleDAO {

	/**
	 * Param�tres de connexion � la base de donn�es oracle
	 * URL, USER et PASS sont des constantes
	 */
	final static String URL = "jdbc:mysql://localhost:3306/mydb?serverTimezone=UTC";
	//final static String URL = "jdbc:mysql://localhost:3307/mydb";
	final static String USER="root";
	final static String PASS="";
	private static Connection con;


	/**
	 * Constructeur de la classe
	 * 
	 */
	 /**
     * Constructeur de la classe
     * @throws SQLException 
     * 
     */
    public ArticleDAO() throws SQLException
    {
        con = DriverManager.getConnection(URL,USER,PASS);
        System.out.println("Connexion effective !");

    }
	

	/**
	 * Permet d'ajouter un article dans la table article
	 * la r�f�rence de l'article est produite automatiquement par la base de donn�es en utilisant une s�quence
	 * Le mode est auto-commit par d�faut : chaque insertion est valid�e
	 * @param nouvArticle l'article � ajouter
	 * @return le nombre de ligne ajout�es dans la table
	 */
	public int ajouter(Article nouvArticle)
	{
		//Connection con = null;
		PreparedStatement ps = null;
		int retour=0;

		//connexion � la base de donn�es
		try {

			//tentative de connexion
			con = DriverManager.getConnection(URL, USER, PASS);
			//pr�paration de l'instruction SQL, chaque ? repr�sente une valeur � communiquer dans l'insertion
			//les getters permettent de r�cup�rer les valeurs des attributs souhait�s de nouvArticle
			ps = con.prepareStatement("INSERT INTO article (art_designation, art_pu_ht, art_qte_stock) VALUES (?, ?, ?)");
			ps.setString(1,nouvArticle.getDesignation());
			ps.setDouble(2,nouvArticle.getPuHt());
			ps.setInt(3,nouvArticle.getQteStock());

			//Ex�cution de la requ�te
			retour=ps.executeUpdate();


		} catch (Exception ee) {
			ee.printStackTrace();
		} finally {
			//fermeture du preparedStatement et de la connexion
			try {if (ps != null)ps.close();} catch (Exception t) {}
			try {if (con != null)con.close();} catch (Exception t) {}
		}
		return retour;

	}
	
	public int modifier(Article article)
	{
		if (article != null)
		{
			con = null;
			PreparedStatement ps = null;
			
			// connexion à la bdd
			try {
				con = DriverManager.getConnection(URL,USER,PASS);
				ps=con.prepareStatement("UPDATE article SET art_designation =  ?, art_pu_ht = ?, art_qte_stock = ? WHERE art_reference = ?");
				ps.setString(1,article.getDesignation());
			    ps.setDouble(2,article.getPuHt());
			    ps.setInt(3,article.getQteStock());
			    ps.setInt(4, article.getReference());

			    // call executeUpdate to execute our sql update statement
			    ps.executeUpdate();
			    ps.close();
			    return 1;
			}
			catch (SQLException se)
			  {
			    // log the exception
			    System.out.println(se.getMessage());
			   
			  }		
		}
		 return 0;
		
	}
	
	public int supprimer(Article article)
	{
		con = null;
		PreparedStatement ps = null;
		
		// connexion à la bdd
		try {
			con = DriverManager.getConnection(URL,USER,PASS);
			ps=con.prepareStatement("DELETE FROM article WHERE art_reference = ?");
		    ps.setInt(1, article.getReference());

		    // call executeUpdate to execute our sql update statement
		    ps.execute();
		    ps.close();
		    return 1;
		}
		catch (SQLException se)
		  {
		    // log the exception
		    System.out.println(se.getMessage());
		   
		  }		

	 return 0;
	}

	/**
	 * Permet de r�cup�rer un article � partir de sa r�f�rence
	 * @param reference la r�f�rence de l'article � r�cup�rer
	 * @return l'article
	 * @return null si aucun article ne correspond � cette r�f�rence
	 */
	public Article getArticle(int reference)
	{

		//Connection con = null;
		PreparedStatement ps = null;
		ResultSet rs=null;
		Article retour=null;

		//connexion � la base de donn�es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			ps = con.prepareStatement("SELECT * FROM article WHERE art_reference = ?");
			ps.setInt(1,reference);

			//on ex�cute la requ�te
			//rs contient un pointeur situ� jusute avant la premi�re ligne retourn�e
			rs=ps.executeQuery();
			//passe � la premi�re (et unique) ligne retourn�e 
			if(rs.next())
				retour=new Article(rs.getInt("art_reference"),rs.getString("art_designation"),rs.getDouble("art_pu_ht"),rs.getInt("art_qte_stock"),rs.getString("TypeArticle_idTypeArticle"),rs.getInt("art_tx_tva"));


		} catch (Exception ee) {
			ee.printStackTrace();
		} finally {
			//fermeture du ResultSet, du PreparedStatement et de la Connection
			try {if (rs != null)rs.close();} catch (Exception t) {}
			try {if (ps != null)ps.close();} catch (Exception t) {}
			try {if (con != null)con.close();} catch (Exception t) {}
		}
		return retour;

	}
	
	public String getTaille(int reference, int c)
	{

		//Connection con = null;
		PreparedStatement ps = null;
		ResultSet rs=null;
		String retour=null;

		//connexion � la base de donn�es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			ps = con.prepareStatement("SELECT * FROM articletaille WHERE id = ?");
			ps.setInt(1,reference);

			//on ex�cute la requ�te
			//rs contient un pointeur situ� jusute avant la premi�re ligne retourn�e
			rs=ps.executeQuery();
			//passe � la premi�re (et unique) ligne retourn�e 
			if(rs.next())
				if(c==1) {
					retour=Integer.toString(rs.getInt("taillePointure"));
				}
				else {
					retour=rs.getString("libelle");
				}
				


		} catch (Exception ee) {
			ee.printStackTrace();
		} finally {
			//fermeture du ResultSet, du PreparedStatement et de la Connection
			try {if (rs != null)rs.close();} catch (Exception t) {}
			try {if (ps != null)ps.close();} catch (Exception t) {}
			try {if (con != null)con.close();} catch (Exception t) {}
		}
		return retour;

	}

	/**
	 * Permet de r�cup�rer tous les articles stock�s dans la table article
	 * @return une ArrayList d'Articles
	 */
	public List<Article> getListeArticles()
	{

		//Connection con = null;
		PreparedStatement ps = null;
		ResultSet rs=null;
		List<Article> retour=new ArrayList<Article>();

		//connexion � la base de donn�es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			ps = con.prepareStatement("SELECT * FROM article");

			//on ex�cute la requ�te
			rs=ps.executeQuery();
			//on parcourt les lignes du r�sultat
			while(rs.next())
				retour.add(new Article(rs.getInt("art_reference"),rs.getString("art_designation"),rs.getDouble("art_pu_ht"),rs.getInt("art_qte_stock"),rs.getString("TypeArticle_idTypeArticle"),rs.getInt("art_tx_tva")));


		} catch (Exception ee) {
			ee.printStackTrace();
		} finally {
			//fermeture du rs, du preparedStatement et de la connexion
			try {if (rs != null)rs.close();} catch (Exception t) {}
			try {if (ps != null)ps.close();} catch (Exception t) {}
			try {if (con != null)con.close();} catch (Exception t) {}
		}
		return retour;

	}
	
	public List<String> getTypes()
	{

		//Connection con = null;
		PreparedStatement ps = null;
		ResultSet rs=null;
		List<String> retour=new ArrayList<String>();

		//connexion � la base de donn�es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			ps = con.prepareStatement("SELECT * FROM typearticle");

			//on ex�cute la requ�te
			rs=ps.executeQuery();
			//on parcourt les lignes du r�sultat
			while(rs.next())
				retour.add(rs.getString("idTypeArticle")+" "+rs.getString("libelle"));


		} catch (Exception ee) {
			ee.printStackTrace();
		} finally {
			//fermeture du rs, du preparedStatement et de la connexion
			try {if (rs != null)rs.close();} catch (Exception t) {}
			try {if (ps != null)ps.close();} catch (Exception t) {}
			try {if (con != null)con.close();} catch (Exception t) {}
		}
		return retour;

	}
	
	public List<Article> getType(int c)
	{

		//Connection con = null;
		PreparedStatement ps = null;
		ResultSet rs=null;
		List<Article> retour=new ArrayList<Article>();

		//connexion � la base de donn�es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			ps = con.prepareStatement("SELECT * FROM article WHERE TypeArticle_idTypeArticle = ?");
			ps.setInt(1, c);
			//on ex�cute la requ�te
			rs=ps.executeQuery();
			//on parcourt les lignes du r�sultat
			while(rs.next())
				retour.add(new Article(rs.getInt("art_reference"),rs.getString("art_designation"),rs.getDouble("art_pu_ht"),rs.getInt("art_qte_stock"),rs.getString("TypeArticle_idTypeArticle"),rs.getInt("art_tx_tva")));


		} catch (Exception ee) {
			ee.printStackTrace();
		} finally {
			//fermeture du rs, du preparedStatement et de la connexion
			try {if (rs != null)rs.close();} catch (Exception t) {}
			try {if (ps != null)ps.close();} catch (Exception t) {}
			try {if (con != null)con.close();} catch (Exception t) {}
		}
		return retour;

	}
	
	public int getTypeFromArticleId(int c)
	{

		//Connection con = null;
		PreparedStatement ps = null;
		ResultSet rs=null;
		int retour=0;

		//connexion � la base de donn�es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			ps = con.prepareStatement("SELECT * FROM article WHERE art_reference = ?");
			ps.setInt(1, c);
			//on ex�cute la requ�te
			rs=ps.executeQuery();
			//on parcourt les lignes du r�sultat
			while(rs.next())
				retour=rs.getInt("TypeArticle_idTypeArticle");


		} catch (Exception ee) {
			ee.printStackTrace();
		} finally {
			//fermeture du rs, du preparedStatement et de la connexion
			try {if (rs != null)rs.close();} catch (Exception t) {}
			try {if (ps != null)ps.close();} catch (Exception t) {}
			try {if (con != null)con.close();} catch (Exception t) {}
		}
		return retour;

	}
	
	public List<String> getTailles(int c)
	{

		//Connection con = null;
		PreparedStatement ps = null;
		ResultSet rs=null;
		List<String> retour=new ArrayList<String>();

		//connexion � la base de donn�es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			if(c==1) {
				ps = con.prepareStatement("SELECT * FROM articletaille WHERE taillePointure IS NOT NULL");
			}
			else {
				ps = con.prepareStatement("SELECT * FROM articletaille WHERE libelle IS NOT NULL");
			}
			

			//on ex�cute la requ�te
			rs=ps.executeQuery();
			//on parcourt les lignes du r�sultat
			while(rs.next())
				if(c==1) {
					retour.add(rs.getString("id")+" / "+rs.getString("taillePointure"));
				}
				else {
					retour.add(rs.getString("id")+" / "+rs.getString("libelle"));
				}
				


		} catch (Exception ee) {
			ee.printStackTrace();
		} finally {
			//fermeture du rs, du preparedStatement et de la connexion
			try {if (rs != null)rs.close();} catch (Exception t) {}
			try {if (ps != null)ps.close();} catch (Exception t) {}
			try {if (con != null)con.close();} catch (Exception t) {}
		}
		return retour;

	}

	//main permettant de tester la classe
	public static void main(String[] args)  throws SQLException {

		 ArticleDAO articleDAO=new ArticleDAO();
		//test de la m�thode ajouter
		Article a = new Article("Set de 2 raquettes de ping-pong",149.9,10);
		int retour=articleDAO.ajouter(a);

		System.out.println(retour+ " lignes ajout�es");

		//test de la m�thode getArticle
		Article a2 = articleDAO.getArticle(1);
		System.out.println(a2);

		 //test de la m�thode getListeArticles
		List<Article> liste=articleDAO.getListeArticles();
		//System.out.println(liste);
		for(Article art : liste)
		{
			System.out.println(art.toString());
		}

	}



}
