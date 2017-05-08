CREATE PROCEDURE [dbo].[spDeleteUser]
	@Username nvarchar(50),
	@Password nvarchar(24)
AS
BEGIN
     SET NOCOUNT ON;
	 IF EXISTS (SELECT * from [dbo].[tblUser] WHERE Username=@Username AND PasswordHash=HASHBYTES('SHA2_512', @Password+CAST(PasswordSalt AS NVARCHAR(36))))
	 BEGIN
		  DECLARE @UserId uniqueidentifier;
		  DECLARE @UserImgId int;

		  SET @UserId = (SELECT Id from [dbo].[tblUser] WHERE Username=@Username AND PasswordHash=HASHBYTES('SHA2_512', @Password+CAST(PasswordSalt AS NVARCHAR(36))));
		  SET @UserImgId = (SELECT UserImgId from [dbo].[tblUser] WHERE Id = @UserId);

		  DELETE FROM [dbo].[tblUser] WHERE Id=@UserId;

		  DELETE FROM [dbo].[tblPost] WHERE ReplyPostId IN (SELECT Id FROM [dbo].[tblPost] WHERE OwnerUserId = @UserId);

		  DELETE FROM [dbo].[tblPost] WHERE OwnerUserId = @UserId;

		  DELETE FROM [dbo].[tblUserContact] WHERE OwnerUserId = @UserId OR TargetUserId = @UserId;

		  DELETE FROM [dbo].[tblGroupUser] WHERE UserId = @UserId;

		  DELETE FROM [dbo].[tblGroupInvite] WHERE OwnerUserId = @UserId OR TargetUserId = @UserId;
		  
		  IF (@UserImgId IS NOT NULL) DELETE FROM [dbo].[tblUserImg] where Id = @UserImgId;

		  RETURN 1;
	 END
	 RETURN 0;
END