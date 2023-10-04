
/**
 * Classe Article
 * @version 1.1
 * */

public class Article {

	/** 
	 * r�f�rence de l'article
	 */
	private int reference;		
	/**
	 * d�signation
	 */
	private String designation;	
	/**
	 * prix unitaire hors taxe
	 */
	private double puHt;		
	/**
	 * quantit� en stock
	 */
	private int qteStock;
	private int qte;
	private String type;
	private int size;

	private int TVA;

	
	/**
	 * Constructeur
	 * @param reference r�f�rence de l'article
	 * @param designation d�signation
	 * @param puHt prix unitaire hors taxe
	 * @param qteStock quantit� en stock
	 */
	public Article(int reference, String designation, double puHt, int qteStock, String type,int TVA) {
		this.reference=reference;
		this.designation = designation;
		this.puHt = puHt;
		this.qteStock = qteStock;
		this.qte = 0;
		this.type=type;
		this.size=0;
		this.TVA=TVA;
	}
	public Article(int reference, String designation, double puHt, int qteStock) {
		this.reference=reference;
		this.designation = designation;
		this.puHt = puHt;
		this.qteStock = qteStock;
		this.type="0";
		this.size=0;
		this.TVA=0;
		this.qte = 0;
	}
	/**
	 * Constructeur - la r�f�rence n'est pas fix�e dans le programme
	 * @param designation d�signation de l'article
	 * @param puHt prix unitaire hors taxe
	 * @param qteStock quantit� en stock
	 */
	public Article(String designation, double puHt, int qteStock) {
		this.designation = designation;
		this.puHt = puHt;
		this.qteStock = qteStock;
		this.type="0";
		this.size=0;
		this.TVA=0;
		this.qte = 0;
	}
	/**
	 * Constructeur - ni la r�f�rence ni la qte en stock ne sont fix�es dans le programme
	 * @param designation d�signation de l'article
	 * @param puHt prix unitaire hors taxe
	 */
	public Article(String designation, double puHt) {
		this.designation = designation;
		this.puHt = puHt;
		this.qteStock = 0;
		this.type="0";
		this.size=0;
		this.TVA=0;
		this.qte = 0;
	}
	/**
	 * getter pour l'attribut reference
	 * @return valeur de la reference article
	 */
	public int getReference() {
		return reference;
	}
	/**
	 * getter pour l'attribut d�signation
	 * @return valeur de la d�signation article
	 */
	public String getDesignation() {
		return designation;
	}
	/**
	 * setter  pour l'attribut designation
	 * @param designation : nouvelle valeur de la d�signation article
	 */
	public void setDesignation(String designation) {
		this.designation = designation;
	}
	/**
	 * getter  pour l'attribut puHt
	 * @return valeur de prix unitaire HT
	 */
	public double getPuHt() {
		return puHt;
	}
	/**
	 * setter  pour l'attribut puHt
	 * @param puHt :  nouvelle valeur de prix unitaire HT
	 */
	public void setPuHt(double puHt) {
		this.puHt = puHt;
	}
	public int getSize() {
		return size;
	}
	public void setSize(int size) {
		this.size = size;
	}
	public void setReference(int ref)
	{
		this.reference = ref;
	}
	/**
	 * getter  pour l'attribut qteStock
	 * @return valeur de quantit� en stock
	 */
	public int getQteStock() {
		return qteStock;
	}
	/**
	 * setter  pour l'attribut qteStock
	 * @param qteStock : nouvelle valeur de prix unitaire HT
	 */
	public void setQteStock(int qteStock) {
		this.qteStock = qteStock;
	}

	/**
	 * Red�finition de la m�thode toString permettant de d�finir la traduction de l'objet en String
	 * pour l'affichage par exemple
	 */
	public String toString() {
		return "Article [réf : " + Integer.toString(reference) + " - " + designation
				+ ", " + Double.toString(puHt) + "e HT, " + Integer.toString(qteStock) + " en stock, Type : "+type+"]";
	}
	public String getType() {
		return type;
	}
	public void setType(String type) {
		this.type = type;
	}
	public int getTVA() {
		return TVA;
	}
	public void setTVA(int tVA) {
		TVA = tVA;
	}
	public int getQte() {
		return qte;
	}
	public void setQte(int qte) {
		this.qte = qte;
	}
	public double getPrixTTC() {
		double taux=(this.TVA+100.0)/100.0;
		return this.puHt*taux;
	}
	public double getTTC() {
		return getPrixTTC()*this.qte;
	}
}
