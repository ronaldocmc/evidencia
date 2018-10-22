DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_super_usuario`(
   IN in_nome VARCHAR(60),
    IN in_cpf VARCHAR(14),
    IN in_status INT,
    IN in_celular VARCHAR(15),
    IN in_email VARCHAR(60),
    IN in_telefone VARCHAR(14),
    IN in_img_perfil VARCHAR(300)
    )
BEGIN
       DECLARE id_user, id_img INT;

       -- DECLARE pessoa CURSOR FOR SELECT populacao.pessoa_pk FROM populacao WHERE populacao.pessoa_cpf = in_cpf;
       -- DECLARE imagem CURSOR FOR SELECT imagens_perfil.imagem_pk FROM imagens_perfil WHERE imagens_perfil.imagem_caminho = in_img_perfil;
      
       --  OPEN pessoa;
       --  OPEN imagem;

      IF (in_cpf != "") AND (in_nome != "") AND (in_status = 1)  THEN
        BEGIN
            
            INSERT INTO `populacao`(`pessoa_nome`, `pessoa_cpf`, `pessoas_status`) VALUES (in_nome,in_cpf,in_status);
            SET id_user = LAST_INSERT_ID();

            -- FETCH pessoa INTO id_user;

            IF (in_email != "") AND (id_user != "") THEN
               BEGIN
                    INSERT INTO `contatos`(`contato_cel`, `contato_email`, `contato_tel`, `pessoa_fk`) VALUES (in_celular,in_email,in_telefone,id_user);
                END;
              ELSE SELECT 'Dados de contato incorretos! Não é possível inserir';
            END IF;
            
            IF (in_img_perfil != "") THEN

                INSERT INTO `imagens_perfil`(`imagem_caminho`, `pessoa_fk`) VALUES (in_img_perfil, id_user);
   
                INSERT INTO `super_usuarios`(`pessoa_fk`, `usuario_status`) VALUES (id_user,in_status);
                -- FETCH imagem INTO id_img;
                -- SET id_img = LAST_INSERT_ID();
                
            END IF;  
        END;
        ELSE SELECT 'Dados de pessoa incorretos! Não é possível inserir.';
      END IF;

   -- CLOSE pessoa;
   -- CLOSE imagem;
END
$$
DELIMITER ;