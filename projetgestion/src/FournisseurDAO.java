import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class FournisseurDAO {
	final static String URL = "jdbc:mysql://localhost:3306/mydb?serverTimezone=UTC";
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
    public FournisseurDAO() throws SQLException
    {
        con = DriverManager.getConnection(URL,USER,PASS);
        System.out.println("Connexion effective !");

    }
    
    public List<Fournisseur> getListeFournisseurs()
	{

		//Connection con = null;
		PreparedStatement ps = null;
		ResultSet rs=null;
		List<Fournisseur> retour=new ArrayList<Fournisseur>();

		//connexion � la base de donn�es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			ps = con.prepareStatement("SELECT * FROM fournisseur");

			//on ex�cute la requ�te
			rs=ps.executeQuery();
			//on parcourt les lignes du r�sultat
			while(rs.next())
				retour.add(new Fournisseur(rs.getInt("idFournisseur"),rs.getString("raisonSoc"),rs.getString("telephone"), rs.getString("adresseRue"), rs.getInt("adresseCP"), rs.getString("adresseVille")));


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
    
    public String getFournisseur(int id)
	{

		//Connection con = null;
		PreparedStatement ps = null;
		ResultSet rs=null;
		String retour="";

		//connexion � la base de donn�es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			ps = con.prepareStatement("SELECT * FROM fournisseur WHERE idFournisseur = ? ");
			ps.setInt(1,id);
			//on ex�cute la requ�te
			rs=ps.executeQuery();
			//on parcourt les lignes du r�sultat
			while(rs.next())
				retour = Integer.toString(rs.getInt("idFournisseur")) + " " + rs.getString("raisonSoc") + " " + rs.getString("telephone") + " " + rs.getString("adresseRue") + " " + Integer.toString(rs.getInt("adresseCP")) + " " + rs.getString("adresseVille");

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
	
	public  List<CommandeClient> getListeCommandes(int idFournisseur){
		
		List<CommandeClient> retour=new ArrayList<CommandeClient>();
		PreparedStatement ps = null;
		ResultSet rs=null;

		//connexion � la base de donn�es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			ps = con.prepareStatement("SELECT * FROM commande INNER JOIN lignecommandefourn ON commande.idCommande = Commande_idCommande WHERE Fournisseur_idFournisseur= ? ORDER BY StatutCommande_idStatutCommande");
			ps.setInt(1,idFournisseur);
			//on ex�cute la requ�te
			rs=ps.executeQuery();
			//on parcourt les lignes du r�sultat
			int tmp=0;
			while(rs.next())
			{
				
				//if(tmp!=rs.getInt("idCommande")) {
					tmp=rs.getInt("idCommande");
				//}else {
					retour.add(getCommande(idFournisseur,tmp));
				//}
				
				
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
	
	
	
	
    public CommandeClient getCommande(int idFournisseur, int id)
	{

		//Connection con = null;
		PreparedStatement ps = null;
		ResultSet rs=null;
		CommandeClient retour=new CommandeClient();
		List<Article> art=new ArrayList<Article>();
		int state=0;
		//connexion � la base de donn�es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			ps = con.prepareStatement("SELECT * FROM commande INNER JOIN lignecommandefourn ON commande.idCommande = Commande_idCommande WHERE Fournisseur_idFournisseur= ? AND  Commande_idCommande= ?");
			ps.setInt(1,idFournisseur); ps.setInt(2,id);
			//on ex�cute la requ�te
			rs=ps.executeQuery();
			//on parcourt les lignes du r�sultat
			while(rs.next())
			{
				state=rs.getInt("StatutCommande_idStatutCommande");
				Article a=getArticle(rs.getInt("Article_art_reference"));
				a.setQte(rs.getInt("qteArticle"));
				a.setSize(rs.getInt("ArticleTaille_id"));
				art.add(a);
				
			}
			retour.setId(id);
			retour.setIdclient(idFournisseur);
			retour.setState(state);
			retour.setArticles(art);

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
    public CommandeClient getCommandeDetail(int idcommande)
	{

		//Connection con = null;
		PreparedStatement ps = null;
		ResultSet rs=null;
		CommandeClient retour=new CommandeClient();
		List<Article> art=new ArrayList<Article>();
		int state=0;
		//connexion � la base de donn�es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			ps = con.prepareStatement("SELECT * FROM commande INNER JOIN article ON commande.idCommande = Commande_idCommande WHERE Fournisseur_idFournisseur= ? AND  Commande_idCommande= ?");
			ps.setInt(1,idFournisseur); ps.setInt(2,id);
			//on ex�cute la requ�te
			rs=ps.executeQuery();
			//on parcourt les lignes du r�sultat
			while(rs.next())
			{
				state=rs.getInt("StatutCommande_idStatutCommande");
				Article a=getArticle(rs.getInt("Article_art_reference"));
				a.setQte(rs.getInt("qteArticle"));
				art.add(a);
				
			}
			retour.setId(id);
			retour.setIdclient(idFournisseur);
			retour.setState(state);
			retour.setArticles(art);

		} catch (Exception ee) {
			ee.printStackTrace();
		} finally {
			//fermeture du rs, du preparedStatement et de la connexion
			try {if (rs != null)rs.close();} catch (Exception t) {}
			try {if (ps != null)ps.close();} catch (Exception t) {}
			try {if (con != null)con.close();} catch (Exception t) {}
		}
		return retour;

	}*/

}
