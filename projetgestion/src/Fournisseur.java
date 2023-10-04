import java.util.ArrayList;
import java.util.List;

public class Fournisseur {
	private int id;
	private String raisonSoc;
	private String telephone;
	private String adresseRue;
	private int adresseCP;
	private String adresseVille;
	private List<CommandeClient> commandes;
	
	
	public Fournisseur(int id, String raisonSoc, String telephone, String adresseRue, int adresseCP,
			String adresseVille) {
		super();
		this.id = id;
		this.raisonSoc = raisonSoc;
		this.telephone = telephone;
		this.adresseRue = adresseRue;
		this.adresseCP = adresseCP;
		this.adresseVille = adresseVille;
		this.commandes = new ArrayList<CommandeClient>();
	}
	
	public List<CommandeClient> getCommandes() {
		return commandes;
	}

	public void setCommandes(List<CommandeClient> commandes) {
		this.commandes = commandes;
	}

	public int getId() {
		return id;
	}
	
	
	public void setId(int id) {
		this.id = id;
	}
	
	public String getRaisonSoc() {
		return raisonSoc;
	}
	
	public void setRaisonSoc(String raisonSoc) {
		this.raisonSoc = raisonSoc;
	}
	
	public String getTelephone() {
		return telephone;
	}
	
	public void setTelephone(String telephone) {
		this.telephone = telephone;
	}
	
	public String getAdresseRue() {
		return adresseRue;
	}
	
	public void setAdresseRue(String adresseRue) {
		this.adresseRue = adresseRue;
	}
	
	public int getAdresseCP() {
		return adresseCP;
	}
	
	public void setAdresseCP(int adresseCP) {
		this.adresseCP = adresseCP;
	}
	
	public String getAdresseVille() {
		return adresseVille;
	}
	
	public void setAdresseVille(String adresseVille) {
		this.adresseVille = adresseVille;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}

