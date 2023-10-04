
public class Commande {

	private int id;
	private String etat;
	private String nom_article;
	private int qte_stock;
	
	
	public Commande(int id, String etat, String nom_article, int qte_stock) {
		this.id = id;
		this.etat = etat;
		this.nom_article = nom_article;
		this.qte_stock = qte_stock;
	}
	
	public Commande(String etat, String nom_article, int qte_stock) {
		this.etat = etat;
		this.nom_article = nom_article;
		this.qte_stock = qte_stock;
	}
	
	public void setId(int id) {
		this.id = id;
	}
	
	public void setEtat(String etat) {
		this.etat = etat;
	}
	
	public void setNom(String nom_article) {
		this.nom_article = nom_article;
	}
	
	public void setQteStock(int qte_stock) {
		this.qte_stock = qte_stock;
	}
	
	public int getId() {
		return this.id;
	}
	
	public String getEtat() {
		return this.etat;
	}
	
	public String getNom() {
		return this.nom_article;
	}
	
	public int getQteStock() {
		return this.qte_stock;
	}
	
	public String toString() {
		return "Commande numéro : " + Integer.toString(id) + " - " + etat
				+ ", " + nom_article + ", Quantité demandée : " + Integer.toString(qte_stock);
	}
}
