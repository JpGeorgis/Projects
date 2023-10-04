
import java.awt.Color;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.sql.SQLException;
import java.awt.Dimension;

import javax.swing.BorderFactory;
import javax.swing.JButton;
import javax.swing.JComboBox;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.JTextField;
import javax.swing.JPanel;
import javax.swing.BoxLayout;
import javax.swing.DefaultListModel;
import javax.swing.Box;

import java.util.ArrayList;
import java.util.List;
import javax.swing.JTabbedPane;
import javax.swing.JTable;

import java.awt.Component;
import javax.swing.SwingConstants;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;
import javax.swing.table.DefaultTableModel;
import javax.swing.table.TableModel;
import javax.swing.JList;
import javax.swing.border.TitledBorder;


/**
 * Classe ArticleFenetre
 * Définit et ouvre une fenetre qui :
 *    - Permet l'insertion d'un nouvel article dans la table article via
 * la saisie des valeurs de désignation, prix et quantité en stock
 *    - Permet l'affichage de tous les articles une zone de texte
 *    
 *    Pour aller plus loin : 
 *    http://docs.oracle.com/javase/tutorial/uiswing/components/frame.html
 *    http://docs.oracle.com/javase/tutorial/uiswing/components/panel.html
 *    Différents types de composants graphiques sont disponibles
 *    http://docs.oracle.com/javase/tutorial/uiswing/components/componentlist.html
 *    Sans oublier la référence d'ouvrage utilisée dans le cours "programmer avec Java"
 *    
 * @version 1.2
 * */


public class ArticleFenetre extends JFrame implements ActionListener
{
	/**
	 * numero de version pour classe serialisable
	 * Permet d'eviter le warning "The serializable class ArticleFenetre does not declare a static final serialVersionUID field of type long"
	 */
	private static final long serialVersionUID = 1L; 
	
	/**
	 * conteneur : il accueille les differents composants graphiques de ArticleFenetre
	 */
	private JTabbedPane containerPanel;		
	
	/**	
	 * zone de texte pour le prix unitaire hors taxe
	 * 	     	
	 */
	private JTextField textFieldPuHt;
	/**
	 * zone de texte pour la quantite en stock
	 */
	private JTextField textFieldQteStock;	 	
	
	/**
	 * label reference
	 */
	private JLabel labelReference;	 			
	
	/**
	 * label designation
	 */
	private JLabel labelDesignation; 		
	
	/**
	 * label prix unitaire hors taxe
	 */
	private JLabel labelPu_ht;	     		
	
	/**
	 * label quantité en stock
	 */
	private JLabel labelQtestock;	 		
	/**
	 * bouton d'envoi de l'article
	 */
	private JButton boutonEnvoi;	 	
	/**
	 *  bouton qui permet d'afficher tous les articles
	 */
	private JButton boutonAffichageTousLesArticles;	
	
	private JButton boutonAffichageTousLesArticlesStock;	
	/**
	 * Zone de défilement pour la zone de texte
	 */
	JScrollPane zoneDefilement;
	/**
	 * instance de ArticleDAO permettant les accès à la base de données
	 */
	private ArticleDAO monArticleDAO;
	private AdherentDAO monAdherentDAO;
	private CommandeDAO maCommandeDAO;
	private FournisseurDAO monFournisseurDAO;
	private JButton boutonModifier, boutonSupprimerArticle;
	private Article currentSelectionne;
	private List<Article> liste;
	//private List<Article> listeDetaillee;
	private List<Commande> listeCommande;
	private List<CommandeClient> listeCommandesClient, listeCommandesClientf;
	private List<Adherent> list;
	private List<Fournisseur> listeFournisseur;
	private List<String> types;
	private List<String> tailles;
	private Adherent membre1;
	private CommandeClient cClient, fClient;
	private JList zoneTextListArticle,zoneTextListArticleStock,zoneTextListCommandes;
	private JTable table,table2,commande, articlesCom, table_1, articlesComFournisseur;
	private JComboBox<String> ListAdherent = new JComboBox<String>(), ListAdherent2 = new JComboBox<String>(), Types = new JComboBox<String>(),size = new JComboBox<String>(), ListFournisseur = new JComboBox<String>();
	private DefaultListModel<Article> articlesListModel = new DefaultListModel<Article>();
	private DefaultListModel<Commande> commandesListModel = new DefaultListModel<Commande>();
	private DefaultTableModel tableModel, tableModel2, commModel, articlesComModel, tableModelCommFourn,articleComFournModel;
	private List<String[]> values = new ArrayList<String[]>();
	private List<String> columns = new ArrayList<String>(); 
	private double total=0, total2=0, totalTVA=0, totalTVA2=0;
	private JTextField textField, textFieldDesignation;
	private JLabel labelPu_ht_1, com_label2;
	private  List<List<String>> commandeRecap = new ArrayList<List<String>>();
	//private String ad = "";
	
	
	/**
	 * Constructeur
	 * Définit la fenêtre et ses composants - affiche la fenêtre
	 * @throws SQLException 
	 */
	public ArticleFenetre() throws SQLException
    {
		// on instancie la classe Article DAO
		this.monArticleDAO = new ArticleDAO();
		this.maCommandeDAO = new CommandeDAO();
		this.monAdherentDAO = new AdherentDAO();
		this.monFournisseurDAO = new FournisseurDAO();
		containerPanel = new JTabbedPane();
		
		this.setSize(1000,1000);
		
		//permet de quitter l'application si on ferme la fenêtre
		this.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
				
		this.setContentPane(containerPanel);
		
		JPanel panel = new JPanel();
		containerPanel.addTab("Accueil", null, panel, null);
		
		JPanel panel_1 = new JPanel();
		containerPanel.addTab("Achats", null, panel_1, null);
		panel_1.setLayout(new BoxLayout(panel_1, BoxLayout.PAGE_AXIS));
		
		JTabbedPane achats = new JTabbedPane(JTabbedPane.TOP);
		panel_1.add(achats);
		
		JPanel panel_achats1 = new JPanel();
		achats.addTab("Saisir Commande", null, panel_achats1, null);
		panel_achats1.setLayout(new BoxLayout(panel_achats1, BoxLayout.PAGE_AXIS));
		
		
/******* DEBUT PAGE SAISI COMMANDE ADHERENT ********/
		
		JPanel adh=new JPanel(),cat=new JPanel(),pan=new JPanel(),com=new JPanel(),qu=new JPanel(), t = new JPanel();
		total=0;
		totalTVA=0;
		JTextField toTVA = new JTextField("0"),to=new JTextField("0"), q = new JTextField("1");

		toTVA.setColumns(5);
		to.setColumns(5);
		q.setColumns(5);
		columns.clear(); values.clear();
		columns.add("Qte"); columns.add("Designation"); columns.add("Taille"); columns.add("Prix HT"); columns.add("Prix TC");
		JLabel label = new JLabel("Adhérent :"), labelcat = new JLabel("Catégorie :"), listart_label = new JLabel("Articles :");
        JLabel quan = new JLabel("Quantité :"),size_art_lab = new JLabel("Taille :"), com_label = new JLabel("Commande :"), tot = new JLabel("Total de la commande (HT, TC): ");
        JButton sub = new JButton("Ajouter au panier"), valider = new JButton("Valider");
        
		
		afficherListeAdherents();
		ListAdherent.addActionListener(new ActionListener(){
			public void actionPerformed(ActionEvent e){
				cClient=new CommandeClient();
                String s = String.valueOf(ListAdherent.getSelectedItem());
                int c=Character.getNumericValue(s.charAt(0));
                cClient.setIdclient(c);
            }
        });
		afficherListeTypes();
		Types.addActionListener(new ActionListener(){
            public void actionPerformed(ActionEvent e){
                String s = String.valueOf(Types.getSelectedItem());
                int c=Character.getNumericValue(s.charAt(0));
                remplirData(c);
                afficherListeTailles(c);
            }
        });
		table = new JTable();
		commande = new JTable();
        /*TableRowSorter<TableModel> s = new TableRowSorter<TableModel>(table.getModel());
        //trier les lignes de JTable
        table.setRowSorter(s);
        java.util.List<RowSorter.SortKey> sortList = new ArrayList<>(100);
        sortList.add(new RowSorter.SortKey(1, SortOrder.ASCENDING));
        sortList.add(new RowSorter.SortKey(0, SortOrder.ASCENDING));
        s.setSortKeys(sortList);*/
		sub.addActionListener(new ActionListener() { 
			  public void actionPerformed(ActionEvent e) { 
				    int row = table.getSelectedRow();
				    int quantite= Integer.parseInt(q.getText());
				    String s = String.valueOf(size.getSelectedItem());
				    int si=Character.getNumericValue(s.charAt(0));
				    String ss = String.valueOf(Types.getSelectedItem());
	                int c=Character.getNumericValue(ss.charAt(0));
				    AjoutPanier(row,quantite, si, c);
				    to.setText(Double.toString(total));
					toTVA.setText(Double.toString(totalTVA));
				  }
		});
		
		
		
		valider.addActionListener(new ActionListener() { 
			  public void actionPerformed(ActionEvent e) { 
				    monAdherentDAO.sendCommande(cClient);
				    ListAdherent.setSelectedIndex(0);
					Types.setSelectedIndex(0);
					commModel.setRowCount(0);
					q.setText("1");
					total=0;
					totalTVA=0;
					to.setText(Double.toString(total));
					toTVA.setText(Double.toString(totalTVA));
					commandeRecap.clear();
					values.clear();
				  }
		});
		adh.add(label);
       	adh.add(ListAdherent);
       	panel_achats1.add(adh);
        
       	cat.add(labelcat);
       	cat.add(Types);
       	panel_achats1.add(cat);
        
       	pan.add(listart_label);
       	pan.add(new JScrollPane(table));
       	panel_achats1.add(pan);
        
       	qu.add(quan);
       	qu.add(q);
       	qu.add(size_art_lab);
       	qu.add(size);
       	panel_achats1.add(qu);
       	panel_achats1.add(sub);
       	        
       	com.add(com_label);
       	com.add(new JScrollPane(commande));
       	panel_achats1.add(com);

       	t.add(tot);
       	t.add(to);
       	t.add(toTVA);
       	panel_achats1.add(t);
       	panel_achats1.add(valider);
       	
/******* FIN PAGE SAISI COMMANDE ADHERENT ********/
       	
		JPanel panel_achats2 = new JPanel();
		achats.addTab("Afficher Commande", null, panel_achats2, null);
		panel_achats2.setLayout(new BoxLayout(panel_achats2, BoxLayout.PAGE_AXIS));
		
/******* DEBUT PAGE AFFICHAGE COMMANDE ADHERENT ********/
		
		JPanel adh2=new JPanel(),pan2=new JPanel(),com2=new JPanel(),t2 = new JPanel();
		JLabel label2 = new JLabel("Adhérent :"), listcom_label= new JLabel("Commandes :"),tot2 = new JLabel("Total de la commande (HT, TC): ");
		JTextField toTVA2 = new JTextField("0"),to2=new JTextField("0");
		toTVA.setColumns(5);
		to.setColumns(5);
		com_label2 = new JLabel("Commande :");
		JButton aff = new JButton("Afficher commande");
		ListAdherent2.removeAllItems();
		afficherListeAdherents2();
		ListAdherent2.addActionListener(new ActionListener(){
			public void actionPerformed(ActionEvent e){
                String s = String.valueOf(ListAdherent2.getSelectedItem());
                int c=Character.getNumericValue(s.charAt(0))-1;
                membre1=list.get(c);
                remplirData2(membre1);
            }
        });
		table2 = new JTable();
		articlesCom = new JTable();
		articlesCom.removeAll();
		
		aff.addActionListener(new ActionListener() { 
			  public void actionPerformed(ActionEvent e) { 
				  	total2=0;
				  	totalTVA2=0;
				    int row = table2.getSelectedRow();
				    AfficheCommande(row);
				    to2.setText(Double.toString(total2));
					toTVA2.setText(Double.toString(totalTVA2));
				  }
		});
		
		adh2.add(label2);
		adh2.add(ListAdherent2);
       	panel_achats2.add(adh2);
		
       	pan2.add(listcom_label);
       	pan2.add(new JScrollPane(table2));
       	panel_achats2.add(pan2);
       	
    	panel_achats2.add(aff);
    	
       	com2.add(com_label2);
       	com2.add(new JScrollPane(articlesCom));
       	panel_achats2.add(com2);
       	
       	t2.add(tot2);
       	t2.add(to2);
       	t2.add(toTVA2);
       	panel_achats2.add(t2);
		
		
/******* FIN PAGE AFFICHAGE COMMANDE ADHERENT ********/	
		
		JPanel panel_2 = new JPanel();
		containerPanel.addTab("Gestion de stocks", null, panel_2, null);
		panel_2.setLayout(new BoxLayout(panel_2, BoxLayout.X_AXIS));
		
		zoneTextListArticleStock = new JList();
		zoneTextListArticleStock.addListSelectionListener(new ListSelectionListener() {
			public void valueChanged(ListSelectionEvent e) {
				zoneTextListArticleSelectionChanged(e);
			}
		});
		
		boutonAffichageTousLesArticlesStock=new JButton("Articles en Stock");
		boutonAffichageTousLesArticlesStock.setBounds(20, 5, 200, 45);
		
		zoneDefilement = new JScrollPane(zoneTextListArticleStock);
		zoneDefilement.setBounds(20, 50, 900, 600);
		panel_2.setLayout(null);
		
		panel_2.add(boutonAffichageTousLesArticlesStock);
		panel_2.add(zoneDefilement);
		
		
		boutonAffichageTousLesArticlesStock.addActionListener(this);
		
		
		//panel_2.setBorder(BorderFactory.createEmptyBorder(10,10,10,10));
		
		this.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

		//affichage de la fenêtre
		this.setVisible(true);
		
		
		/*
		JPanel panel_stock = new JPanel();
		panel_2.addTab("Articles Details", null, panel_stock, null);
		panel_stock.setLayout(new BoxLayout(panel_stock, BoxLayout.PAGE_AXIS));
		
		JTable table = new JTable();
		Object[] colomns = {"PU", "HT", "Qt�s disponibles", "description", "intitule"};
		DefaultTableModel model = new DefaultTableModel();
		
		JFrame frame = new JFrame("WINDOW");
		frame.getContentPane().setBackground(new Color(0,0,0));
		frame.setBounds(100,100,757,610);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		frame.getContentPane().setLayout(null);
		frame.setLocationRelativeTo(null);
		
		model.setColumnIdentifiers(colomns);
		table.setModel(model);
		
		table.setBackground(Color.white);
		table.setForeground(Color.black);
		table.setSelectionBackground(Color.red);
		
		table.setRowHeight(30);
		table.setAutoCreateRowSorter(true);
		
		JScrollPane pane = new JScrollPane(table);
		pane.setForeground(Color.RED);
		pane.setBackground(Color.WHITE);
		pane.setBounds(10,10,10,10);
		frame.getContentPane().add(pane);
		
		Object[] row = new Object[4];
		
		frame.setVisible(true);*/
		
		
		//panel_2.setBorder(BorderFactory.createEmptyBorder(10,10,10,10));

		/*
		JPanel panel_stock = new JPanel();
		panel_2.addTab("Articles Details", null, panel_stock, null);
		panel_stock.setLayout(new BoxLayout(panel_stock, BoxLayout.PAGE_AXIS));
		
		JTable table = new JTable();
		Object[] colomns = {"PU", "HT", "Qt�s disponibles", "description", "intitule"};
		DefaultTableModel model = new DefaultTableModel();
		
		JFrame frame = new JFrame("WINDOW");
		frame.getContentPane().setBackground(new Color(0,0,0));
		frame.setBounds(100,100,757,610);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		frame.getContentPane().setLayout(null);
		frame.setLocationRelativeTo(null);
		
		model.setColumnIdentifiers(colomns);
		table.setModel(model);
		
		table.setBackground(Color.white);
		table.setForeground(Color.black);
		table.setSelectionBackground(Color.red);
		
		table.setRowHeight(30);
		table.setAutoCreateRowSorter(true);
		
		JScrollPane pane = new JScrollPane(table);
		pane.setForeground(Color.RED);
		pane.setBackground(Color.WHITE);
		pane.setBounds(10,10,10,10);
		frame.getContentPane().add(pane);
		
		Object[] row = new Object[4];
		
		frame.setVisible(true);*/
		
		/******* FIN PAGE AFFICHAGE ARTICLES ********/	
		JPanel panel_3 = new JPanel();
		containerPanel.addTab("Commandes fournisseur", null, panel_3, null);
		panel_3.setLayout(new BoxLayout(panel_3, BoxLayout.PAGE_AXIS));
		
		JTabbedPane tabbedPane = new JTabbedPane(JTabbedPane.TOP);
		panel_3.add(tabbedPane);
		
		JPanel panel_4 = new JPanel();
		tabbedPane.addTab("Etats Commandes", null, panel_4, null);
		panel_4.setLayout(new BoxLayout(panel_4, BoxLayout.PAGE_AXIS));
		
		JPanel fournisseur = new JPanel();
		//fournisseur.setBounds(0, 0, 974, 32);
		panel_4.add(fournisseur);
		
		JLabel lblFournisseur = new JLabel("Fournisseur :");
		fournisseur.add(lblFournisseur);
		
		//JComboBox<String> ListFournisseur = new JComboBox<String>();
		fournisseur.add(ListFournisseur);
		afficherListeFournisseurs();
		ListFournisseur.addActionListener(new ActionListener(){
			public void actionPerformed(ActionEvent e){
				fClient=new CommandeClient();
                String s = String.valueOf(ListFournisseur.getSelectedItem());
                int c=Character.getNumericValue(s.charAt(0));
                fClient.setIdclient(c);
                remplirDataFournisseur(c);
            }
        });
		
		
		JPanel pan_1 = new JPanel();
		//pan_1.setBounds(0, 29, 974, 367);
		panel_4.add(pan_1);
		pan_1.setLayout(new BoxLayout(pan_1, BoxLayout.X_AXIS));
		
		JLabel listart_label_1 = new JLabel("Commandes :");
		pan_1.add(listart_label_1);
		
		JScrollPane scrollPane = new JScrollPane((Component) null);
		pan_1.add(scrollPane);
		
		JButton button_detailCommande = new JButton(("Details commande"));
		panel_4.add(button_detailCommande);
		
		JPanel pan_detail_commande_fournisseur = new JPanel();
		panel_4.add(pan_detail_commande_fournisseur);
		pan_detail_commande_fournisseur.setLayout(new BoxLayout(pan_detail_commande_fournisseur, BoxLayout.X_AXIS));
		
		JLabel label_detail_commande = new JLabel("Detail :");
		pan_detail_commande_fournisseur.add(label_detail_commande);
		
		//JScrollPane scroll_detail_commande = new JScrollPane((Component) null);
		
		
		table_1 = new JTable();
		articlesComFournisseur = new JTable();
		articlesComFournisseur.removeAll();
		
		pan_detail_commande_fournisseur.add(new JScrollPane(articlesComFournisseur));
		
		scrollPane.setViewportView(table_1);
		//scrollPane.setViewportView(articlesComFournisseur);
		
		
		
		/******* ACCEUILL ********/	
		
		textFieldPuHt=new JTextField();
		textFieldQteStock=new JTextField();
		boutonEnvoi=new JButton("envoyer");
		boutonEnvoi.setBounds(56, 222, 90, 23);
		boutonAffichageTousLesArticles=new JButton("afficher tous les articles");
		boutonAffichageTousLesArticles.setBounds(56, 281, 204, 23);
		labelReference=new JLabel("La Référence sera générée par la base de données");
		labelReference.setBounds(56, 256, 246, 14);
		labelDesignation=new JLabel("Désignation :");
		labelDesignation.setBounds(56, 97, 176, 14);
		labelPu_ht=new JLabel("Prix unitaire HT :");
		        
		button_detailCommande.addActionListener(new ActionListener() { 
			  public void actionPerformed(ActionEvent e) { 
				    int row = table_1.getSelectedRow();
				    AfficheCommandeFourn(row);
				  }
		});
		
		zoneTextListArticle = new JList();
		zoneTextListArticle.addListSelectionListener(new ListSelectionListener() {
			public void valueChanged(ListSelectionEvent e) {
				zoneTextListArticleSelectionChanged(e);
			}
		});
				
		//zoneDefilement.setRowHeaderView(zoneTextListArticle);
		zoneDefilement = new JScrollPane(zoneTextListArticle);
		zoneDefilement.setBounds(56, 314, 449, 130);
		panel.setLayout(null);
		//ajout des composants sur le container 
		panel.add(labelDesignation);
				
		panel.add(boutonEnvoi);
				
		boutonModifier = new JButton("Modifier");
		boutonModifier.setBounds(292, 281, 83, 23);
		panel.add(boutonModifier);
		boutonSupprimerArticle = new JButton("Supprimer");
		boutonSupprimerArticle.setBounds(385, 281, 97, 23);
		panel.add(boutonSupprimerArticle);
		panel.add(labelReference);

		panel.add(boutonAffichageTousLesArticles);
		panel.add(zoneDefilement);
				
		//ajouter une bordure vide de taille constante autour de l'ensemble des composants
		panel.setBorder(BorderFactory.createEmptyBorder(10,10,10,10));
		
		textField = new JTextField();
		textField.setBounds(56, 191, 449, 20);
		panel.add(textField);
		
		labelPu_ht_1 = new JLabel("Prix unitaire HT :");
		labelPu_ht_1.setBounds(56, 165, 176, 14);
		panel.add(labelPu_ht_1);
		
		textFieldDesignation = new JTextField();
		textFieldDesignation.setHorizontalAlignment(SwingConstants.LEFT);
		textFieldDesignation.setAlignmentX(0.0f);
		textFieldDesignation.setBounds(56, 131, 449, 20);
		panel.add(textFieldDesignation);
				
		//ajout des écouteurs sur les boutons pour gérer les évènements
		boutonEnvoi.addActionListener(this);
		boutonAffichageTousLesArticles.addActionListener(this);
		boutonModifier.addActionListener(this);
		boutonSupprimerArticle.addActionListener(this);
				
		//permet de quitter l'application si on ferme la fenÃªtre
		this.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

		//affichage de la fenÃªtre
		this.setVisible(true);
	}
	
	/**
	 * Gère les actions réalisées sur les boutons
	 *
	 */
	public void actionPerformed(ActionEvent ae)
	{
		int retour; // code de retour de la classe ArticleDAO
		
		try {
			if(ae.getSource()==boutonEnvoi)
			{
				//on crée l'objet message
				Article a=new Article(this.textFieldDesignation.getText(), Double.parseDouble(this.textFieldPuHt.getText()));
				//on demande à la classe de communication d'envoyer l'article dans la table article
				retour = monArticleDAO.ajouter(a);
				// affichage du nombre de lignes ajoutées
				// dans la bdd pour vérification
				System.out.println("" + retour + " ligne ajoutée ");
			}
			else if(ae.getSource()==boutonModifier)
			{
				// modifier en bdd l'article sélectionné
				Article a=new Article(this.textFieldDesignation.getText(), Double.parseDouble(this.textFieldPuHt.getText()));
				a.setReference(currentSelectionne.getReference());
				retour = monArticleDAO.modifier(a);
				
				System.out.println(""+ retour + " ligne modifiée ");
				afficherListeArticles();
				//afficherListeCommandes();
				
			}
			else if(ae.getSource()==boutonSupprimerArticle)
			{
				supprimerArticle(currentSelectionne);
				afficherListeArticles();
				//afficherListeCommandes();
			}
			else if(ae.getSource()==boutonAffichageTousLesArticles)
			{
				afficherListeArticles();

				afficherListeCommandes();
			}
			else if(ae.getSource()==boutonAffichageTousLesArticlesStock)
			{
				afficherListeArticlesStock();
			}
		}
		catch (Exception e) {
			System.err.println("Veuillez contrôler vos saisies");
		}
		
	}
	
	/**
	 * Afficher l'article
	 * @param e
	 */
	private void zoneTextListArticleSelectionChanged(ListSelectionEvent e) {
		
		currentSelectionne = (Article) zoneTextListArticle.getSelectedValue();
		AfficherArticle(currentSelectionne);
	 }
	
	private void AfficherArticle(Article a)
	{
		if(a != null)
		{
			textFieldDesignation.setText(a.getDesignation());	
			textFieldPuHt.setText(String.valueOf(a.getPuHt()));
		}	
	}
	
	private void afficherListeArticlesStock()
	{
		// on demande à la classe ArticleDAO d'ajouter le message
		// dans la base de données
		liste = monArticleDAO.getListeArticles();
	
		
		//on affiche dans la console du client les articles reçus
		articlesListModel.clear();
		for(Article a : liste)
		{  
			 articlesListModel.addElement(a);
			      
			//Pour afficher dans la console : System.out.println(a.toString());	
		}
		zoneTextListArticleStock.setModel(articlesListModel);
	}
	
	
	
	private void afficherListeArticles()
	{
		// on demande à la classe ArticleDAO d'ajouter le message
		// dans la base de données
		liste = monArticleDAO.getListeArticles();
	
		
		//on affiche dans la console du client les articles reçus
		articlesListModel.clear();
		for(Article a : liste)
		{  
			 articlesListModel.addElement(a);
			      
			//Pour afficher dans la console : System.out.println(a.toString());	
		}
		zoneTextListArticle.setModel(articlesListModel);
	}
	
	private void afficherListeAdherents()
	{
		// on demande à la classe ArticleDAO d'ajouter le message
		// dans la base de données
		list = monAdherentDAO.getListeAdherents();
	
		for(Adherent a : list)
		{  
			//Pour afficher dans la console : System.out.println(a.toString());	
			String s=a.getId()+" "+a.getNom()+" "+a.getPrenom();
			ListAdherent.addItem(s);
		}
	}
	private void afficherListeAdherents2()
	{
		// on demande à la classe ArticleDAO d'ajouter le message
		// dans la base de données
		list = monAdherentDAO.getListeAdherents();
	
		for(Adherent a : list)
		{  
			//Pour afficher dans la console : System.out.println(a.toString());	
			String s=a.getId()+" "+a.getNom()+" "+a.getPrenom();
			ListAdherent2.addItem(s);
		}
	}
	
	private void afficherListeTypes()
	{
		// on demande à la classe ArticleDAO d'ajouter le message
		// dans la base de données
		types = monArticleDAO.getTypes();
	
		for(String s : types)
		{  
			//Pour afficher dans la console : System.out.println(a.toString());	
			Types.addItem(s);
		}
	}
	
	private void afficherListeTailles(int c)
	{
		// on demande à la classe ArticleDAO d'ajouter le message
		// dans la base de données
		tailles = monArticleDAO.getTailles(c);
		size.removeAllItems();
		for(String s : tailles)
		{  
			//Pour afficher dans la console : System.out.println(a.toString());	
			size.addItem(s);
		}
	}
	
	private void afficherListeCommandes()
	{
		// on demande à la classe ArticleDAO d'ajouter le message
		// dans la base de données
		listeCommande = maCommandeDAO.getListeCommandes();
	
		
		//on affiche dans la console du client les articles reçus
		commandesListModel.clear();
		for(Commande c : listeCommande)
		{  
			 commandesListModel.addElement(c);
			      
			//Pour afficher dans la console : System.out.println(a.toString());	
		}
		//zoneTextListCommandes.setModel(commandesListModel);
	}
	
	private void afficherListeFournisseurs()
	{
		// on demande à la classe ArticleDAO d'ajouter le message
		// dans la base de données
		listeFournisseur = monFournisseurDAO.getListeFournisseurs();
	
		for(Fournisseur f : listeFournisseur)
		{  
			//Pour afficher dans la console : System.out.println(a.toString());	
			String s=f.getId()+" "+f.getRaisonSoc()+" "+f.getTelephone() + " " + f.getAdresseRue() + " " + f.getAdresseCP() + " " + f.getAdresseVille();
			ListFournisseur.addItem(s);
		}
	}
	
	private void supprimerArticle(Article a)
	{
		monArticleDAO.supprimer(a);
	}

	private void remplirData(int c)
	{
		liste = monArticleDAO.getType(c);
		List<String[]> values = new ArrayList<String[]>();
		List<String> columns = new ArrayList<String>();
		 columns.add("Id");
		 columns.add("Designation");
		 columns.add("Prix HT");
		 columns.add("Qte stock");
		 columns.add("TVA");
		for (Article a : liste) {
            values.add(new String[] {Integer.toString(a.getReference()),a.getDesignation(),Double.toString(a.getPuHt()),Integer.toString(a.getQteStock()),Integer.toString(a.getTVA())} );
        }
		tableModel = new DefaultTableModel(values.toArray(new Object[][] {}), columns.toArray());
		table.setModel(tableModel);
	}
	
	private void remplirData2(Adherent c)
	{
		listeCommandesClient = monAdherentDAO.getListeCommandes(c.getId());
		listeCommandesClient=removeDuplicates(listeCommandesClient);
		List<String[]> values = new ArrayList<String[]>();
		List<String> columns = new ArrayList<String>();
		columns.add("Id");
		columns.add("Etat");
		columns.add("Nb Articles");
		columns.add("Montant");
		 
		for (CommandeClient a : listeCommandesClient) {
            values.add(new String[] { Integer.toString(a.getId()), Integer.toString(a.getState()),  Integer.toString(a.getNbArticles()), Double.toString(a.getPrixTTC())} );
        }
		tableModel2 = new DefaultTableModel(values.toArray(new Object[][] {}), columns.toArray());
		table2.setModel(tableModel2);
	}
	private void remplirDataFournisseur(int f) {
		listeCommandesClientf = monFournisseurDAO.getListeCommandes(f);
		String s = "";
		List<String[]> values = new ArrayList<String[]>();
		List<String> columns = new ArrayList<String>();
		columns.add("Id");
		columns.add("Etat");
		columns.add("Nb Articles");
		columns.add("Montant");
		
		for (CommandeClient ff : listeCommandesClientf) {
			switch(ff.getState()) {
				case 1 :
					s = "Ouvert";
					break;
				case 2 :
					s = "Payée";
					break;
				case 3 :
					s = "Livrée";
					break;
				case 4 :
					s = "Cloturée";
					break;
			}
			values.add(new String[] { Integer.toString(ff.getId()), s,  Integer.toString(ff.getNbArticles()), Double.toString(ff.getPrixTTC())} );
		}
		tableModelCommFourn = new DefaultTableModel(values.toArray(new Object[][] {}), columns.toArray());
		table_1.setModel(tableModelCommFourn);
	}
    public List<CommandeClient> removeDuplicates(List<CommandeClient> list)
    {
  
        // Create a new ArrayList
    	List<CommandeClient> newList = new ArrayList<CommandeClient>();
  
        // Traverse through the first list
        for (CommandeClient element : list) {
  
            // If this element is not present in newList
            // then add it
            if (!isSame(element,newList)) {
                newList.add(element);
            }
        }
  
        // return the new list
        return newList;
    }
    public boolean isSame(CommandeClient c ,List<CommandeClient> nl) {
    	
    	for(CommandeClient a : nl) {
    		if(a.getId()==c.getId()) {
    			return true;
    		}
    	}
    	
    	
    	
    	return false;
    }
	private void remplirCom(Article a, int c)
	{
        values.add(new String[] {Integer.toString(a.getQte()),a.getDesignation(),monArticleDAO.getTaille(a.getSize(),c),Double.toString(a.getPuHt()*a.getQte()),Double.toString(a.getTTC())} );
		commModel = new DefaultTableModel(values.toArray(new Object[][] {}), columns.toArray());
		commande.setModel(commModel);
	}
	
	private void AjoutPanier(int rowIndex, int quantite, int t, int c) {
		
		Article a=liste.get(rowIndex);
		a.setQte(quantite);
		a.setSize(t);
		totalTVA += a.getTTC();
		total += a.getPuHt() * quantite;
		cClient.getArticles().add(a);
		remplirCom(a,c);
	} 
	
	private void AfficheCommande(int rowIndex) {
		CommandeClient c= listeCommandesClient.get(rowIndex);
		//int id=Integer.parseInt(table2.getModel().getValueAt(rowIndex, commIndex).toString());
		totalTVA2 +=c.getPrixTTC();
		total2 +=c.getPrixHT();
		com_label2.setText("Commande n°"+c.getId()+" :");
		List<String[]> values = new ArrayList<String[]>();
		List<String> columns = new ArrayList<String>();
		columns.add("Id");
		columns.add("Desgination");
		columns.add("Taille");
		columns.add("Quantité");
		columns.add("PuHT");
		columns.add("Total HT");
		columns.add("Total TTC");
		
		for(Article a : c.getArticles()) {
			values.add(new String[] { Integer.toString(a.getReference()), a.getDesignation(),monArticleDAO.getTaille(a.getSize(),monArticleDAO.getTypeFromArticleId(a.getReference())), Integer.toString(a.getQte()), Double.toString(a.getPuHt()), Double.toString(a.getPuHt()*a.getQte()), Double.toString(a.getPrixTTC()*a.getQte())} );
		}
		
		
		articlesComModel = new DefaultTableModel(values.toArray(new Object[][] {}), columns.toArray());
		articlesCom.setModel(articlesComModel);
	}
	
	private void AfficheCommandeFourn(int rowIndex) {
		CommandeClient c= listeCommandesClientf.get(rowIndex);
		//int id=Integer.parseInt(table2.getModel().getValueAt(rowIndex, commIndex).toString());
		List<String[]> values = new ArrayList<String[]>();
		List<String> columns = new ArrayList<String>();
		columns.add("Id");
		columns.add("Desgination");
		columns.add("Taille");
		columns.add("Quantité");
		columns.add("PuHT");
		columns.add("Total HT");
		columns.add("Total TTC");
		
		for(Article a : c.getArticles()) {
			values.add(new String[] { Integer.toString(a.getReference()), a.getDesignation(),monArticleDAO.getTaille(a.getSize(),monArticleDAO.getTypeFromArticleId(a.getReference())), Integer.toString(a.getQte()), Double.toString(a.getPuHt()), Double.toString(a.getPuHt()*a.getQte()), Double.toString(a.getPrixTTC()*a.getQte())} );
		}
		
		/*
		for(int i=0;i<values.size();i++)
		{
			for(int j=0;j<values.get(i).length;j++)
				System.out.print(values.get(i)[j]);
		}*/
			
		articleComFournModel = new DefaultTableModel(values.toArray(new Object[][] {}), columns.toArray());
		articlesComFournisseur.setModel(articleComFournModel);
	}
	
	public static void main(String[] args) throws SQLException
	{
		new ArticleFenetre();
    }
}
