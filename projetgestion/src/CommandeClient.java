import java.util.ArrayList;
import java.util.List;

public class CommandeClient {
	private int id;
	private int idclient;
	private int state;
	private List<Article> articles;
	
	
	public CommandeClient() {
		this.id=0;
		this.idclient=0;
		this.state=0;
		this.articles = new ArrayList<Article>();
	}
	
	public CommandeClient(int id, int idclient,int state, List<Article> articles ) {
		this.id=id;
		this.idclient=idclient;
		this.state=state;
		this.articles = articles;
	}
	
	public int getId() {
		return id;
	}
	public void setId(int id) {
		this.id = id;
	}
	
	public int getIdclient() {
		return idclient;
	}
	public void setIdclient(int idclient) {
		this.idclient = idclient;
	}
	
	public List<Article> getArticles() {
		return articles;
	}
	public void setArticles(List<Article> articles) {
		this.articles = articles;
	}
	
	public int getNbArticles() {
		int nb=0;
		for (Article a : this.articles) {
            nb+=a.getQte();
        }
		return nb;
	}
	
	public double getPrixHT() {
		double prix=0;
		for (Article a : this.articles) {
			prix += a.getPuHt()*a.getQte();
        }
		return prix;
	}
	
	public double getPrixTTC() {
		double prix=0;
		for (Article a : this.articles) {
			prix += a.getTTC(); 
        }
		return prix;
	}

	public int getState() {
		return state;
	}

	public void setState(int state) {
		this.state = state;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
}
