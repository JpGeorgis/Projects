import java.sql.*;
import java.util.ArrayList;
import java.util.List;

/**
 * Classe d'acc�s aux donn�es contenues dans la table article
 * @version 1.1
 * */
public class CommandeDAO {

	/**
	 * Param�tres de connexion � la base de donn�es oracle
	 * URL, LOGIN et PASS sont des constantes
	 */
	final static String URL = "jdbc:mysql://localhost:3306/mydb?serverTimezone=UTC";
	//final static String URL = "jdbc:mysql://localhost:3307/mydb";
	final static String LOGIN="root";
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
    public CommandeDAO() throws SQLException
    {
        con = DriverManager.getConnection(URL,LOGIN,PASS);
        System.out.println("Connexion effective !");

    }
	

	/**
	 * Permet de r�cup�rer toutes les commandes stock�s dans la table commandes
	 * @return une ArrayList de Commandes
	 */
	public List<Commande> getListeCommandes()
	{

		//Connection con = null;
		PreparedStatement ps = null;
		ResultSet rs=null;
		List<Commande> retour=new ArrayList<Commande>();

		//connexion � la base de donn�es
		try {

			con = DriverManager.getConnection(URL, LOGIN, PASS);
			ps = con.prepareStatement("SELECT * FROM commande INNER JOIN statutcommande on commande.StatutCommande_idStatutCommande = statutcommande.idStatutCommande INNER JOIN lignecommandeclient on commande.idCommande = lignecommandeclient.Commande_idCommande INNER JOIN article on lignecommandeclient.Article_art_reference = article.art_reference");

			//on ex�cute la requ�te
			rs=ps.executeQuery();
			//on parcourt les lignes du r�sultat
			while(rs.next())
				retour.add(new Commande(rs.getInt("idCommandeFourn"),rs.getString("statutcommande.libelle"),rs.getString("article.art_designation"),rs.getInt("lignecommandeclient.qteArticle")));


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

	/*
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
	*/
}

