CREATE PROCEDURE [dbo].[spGetImageData]
	@UserImgId int = 0
AS
	SELECT ImageContent from [dbo].[tblUserImg] where Id = @UserImgId;
