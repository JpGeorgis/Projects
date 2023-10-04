import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class AdherentDAO {
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
    public AdherentDAO() throws SQLException
    {
        con = DriverManager.getConnection(URL,USER,PASS);
        System.out.println("Connexion effective !");

    }
    
    public List<Adherent> getListeAdherents()
	{

		//Connection con = null;
		PreparedStatement ps = null;
		ResultSet rs=null;
		List<Adherent> retour=new ArrayList<Adherent>();

		//connexion ï¿½ la base de donnï¿½es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			ps = con.prepareStatement("SELECT * FROM membre");

			//on exï¿½cute la requï¿½te
			rs=ps.executeQuery();
			//on parcourt les lignes du rï¿½sultat
			while(rs.next())
				retour.add(new Adherent(rs.getInt("idMembre"),rs.getString("nom"),rs.getString("prenom")));


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
    
    public String getAdherent(int id)
	{

		//Connection con = null;
		PreparedStatement ps = null;
		ResultSet rs=null;
		String retour="";

		//connexion ï¿½ la base de donnï¿½es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			ps = con.prepareStatement("SELECT * FROM membre WHERE idMembre = ? ");
			ps.setInt(1,id);
			//on exï¿½cute la requï¿½te
			rs=ps.executeQuery();
			//on parcourt les lignes du rï¿½sultat
			while(rs.next())
				retour= Integer.toString(rs.getInt("idMembre")) + " " + rs.getString("nom")+ " " + rs.getString("prenom");


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

		//connexion ï¿½ la base de donnï¿½es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			ps = con.prepareStatement("SELECT * FROM article WHERE art_reference = ?");
			ps.setInt(1,reference);

			//on exï¿½cute la requï¿½te
			//rs contient un pointeur situï¿½ jusute avant la premiï¿½re ligne retournï¿½e
			rs=ps.executeQuery();
			//passe ï¿½ la premiï¿½re (et unique) ligne retournï¿½e 
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
	
	public  List<CommandeClient> getListeCommandes(int idClient){
		
		List<CommandeClient> retour=new ArrayList<CommandeClient>();
		PreparedStatement ps = null;
		ResultSet rs=null;
		
		//connexion ï¿½ la base de donnï¿½es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			ps = con.prepareStatement("SELECT * FROM commande INNER JOIN lignecommandeclient ON commande.idCommande = Commande_idCommande WHERE Membre_idMembre= ?");
			ps.setInt(1,idClient);
			//on exï¿½cute la requï¿½te
			rs=ps.executeQuery();
			//on parcourt les lignes du rï¿½sultat
			int tmp=0;
			while(rs.next())
			{
				
				//if(tmp!=rs.getInt("idCommande")) {
					tmp=rs.getInt("idCommande");
				//}else {
					retour.add(getCommande(idClient,tmp));
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
	
	
	
	
    public CommandeClient getCommande(int idClient, int id)
	{

		//Connection con = null;
		PreparedStatement ps = null;
		ResultSet rs=null;
		CommandeClient retour=new CommandeClient();
		List<Article> art=new ArrayList<Article>();
		int state=0;
		//connexion ï¿½ la base de donnï¿½es
		try {

			con = DriverManager.getConnection(URL, USER, PASS);
			ps = con.prepareStatement("SELECT * FROM commande INNER JOIN lignecommandeclient ON commande.idCommande = Commande_idCommande WHERE Membre_idMembre= ? AND  Commande_idCommande= ?");
			ps.setInt(1,idClient); ps.setInt(2,id);
			//on exï¿½cute la requï¿½te
			rs=ps.executeQuery();
			//on parcourt les lignes du rï¿½sultat
			while(rs.next())
			{
				state=rs.getInt("StatutCommande_idStatutCommande");
				Article a=getArticle(rs.getInt("Article_art_reference"));
				a.setQte(rs.getInt("qteArticle"));
				a.setSize(rs.getInt("ArticleTaille_id"));
				art.add(a);
				
			}
			retour.setId(id);
			retour.setIdclient(idClient);
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

	public void sendCommande(CommandeClient cClient) {
		//crÃ©e une commande
		PreparedStatement ps = null;
		ResultSet rs=null;
		int idc=0;
		int qt = 0;
		try {
				con = DriverManager.getConnection(URL, USER, PASS);
				ps = con.prepareStatement("INSERT INTO commande (dateCreation, StatutCommande_idStatutCommande ) VALUES (?, ?)");
			    
				long millis=System.currentTimeMillis();  
				java.sql.Date date = new java.sql.Date(millis);   
				ps.setDate(1,date);
				ps.setInt(2, 1);
				//on exï¿½cute la requï¿½te
				ps.execute();
				ps.close();
				
				ps = con.prepareStatement("SELECT MAX(idCommande) as id FROM commande");
				rs = ps.executeQuery();
				while(rs.next())
					idc=rs.getInt("id");
			    ps.close();
			    for(int i=0;i<cClient.getArticles().size();i++) {
			    	ps = con.prepareStatement("SELECT * FROM Article WHERE art_reference = ?");
			    	ps.setInt(1, cClient.getArticles().get(i).getReference());
			    	rs = ps.executeQuery();
			    	while (rs.next()) {
			    		qt = rs.getInt("art_qte_stock");
			    	}
			    	ps.close();
			    	
			    	if (qt - cClient.getArticles().get(i).getQte() >= 0) {
			    		ps = con.prepareStatement("UPDATE Article SET art_qte_stock = ? WHERE art_reference = ?");
			    		ps.setInt(1, qt - cClient.getArticles().get(i).getQte());
			    		ps.setInt(2, cClient.getArticles().get(i).getReference());
			    		ps.execute();
			    		ps.close();
			    		
			    		ps = con.prepareStatement("INSERT INTO lignecommandeclient (Membre_idMembre, Commande_idCommande, qteArticle, ArticleTaille_id, Article_art_reference ) VALUES (?, ?, ?, ?, ?)");
						ps.setInt(1,cClient.getIdclient());
						ps.setInt(2, idc);
						ps.setInt(3,cClient.getArticles().get(i).getQte());
						ps.setInt(4, cClient.getArticles().get(i).getSize());
						ps.setInt(5, cClient.getArticles().get(i).getReference());
						    
						//on exï¿½cute la requï¿½te
						ps.execute();
						ps.close();
			    	}
					/*
					ps = con.prepareStatement("INSERT INTO lignecommandeclient (Membre_idMembre, Commande_idCommande, qteArticle, ArticleTaille_id, Article_art_reference ) VALUES (?, ?, ?, ?, ?)");
					ps.setInt(1,cClient.getIdclient());
					ps.setInt(2, idc);
					ps.setInt(3,cClient.getArticles().get(i).getQte());
					ps.setInt(4, cClient.getArticles().get(i).getSize());
					ps.setInt(5, cClient.getArticles().get(i).getReference());
					    
					//on exï¿½cute la requï¿½te
					ps.execute();
					ps.close();
					*/
			    }
			    
			    
		} catch (Exception ee) {
			ee.printStackTrace();
		}finally {
			//fermeture du rs, du preparedStatement et de la connexion
			try {if (rs != null)rs.close();} catch (Exception t) {}
			try {if (ps != null)ps.close();} catch (Exception t) {}
			try {if (con != null)con.close();} catch (Exception t) {}
		}
	
	}
}
