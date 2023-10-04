import java.util.ArrayList;
import java.util.List;

public class Adherent {
	
	/** 
	 * id adherent
	 */
	private int id;		

	/**
	 * nom
	 */
	private String nom;	
	/**
	 * prenom
	 */
	private String prenom;		
	
	private List<CommandeClient> commandes;	
	
	public Adherent(int id, String nom, String prenom) {
		this.id=id;
		this.nom = nom;
		this.prenom = prenom;
		this.commandes=new ArrayList<CommandeClient>();
	}
	
	
	public int getId() {
		return id;
	}

	public String getNom() {
		return nom;
	}


	public String getPrenom() {
		return prenom;
	}


	public List<CommandeClient> getCommande() {
		return commandes;
	}


	public void setCommande(List<CommandeClient> commandes) {
		this.commandes = commandes;
	}

}
